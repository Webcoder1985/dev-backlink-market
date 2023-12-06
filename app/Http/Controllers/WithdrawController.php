<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use \PaypalPayoutsSDK\Core\PayPalHttpClient;
use \PaypalPayoutsSDK\Core\SandboxEnvironment;
use \PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use App\Models\UserBalanceHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Mail\MailAdminNotification;
use App\Http\Controllers\ReceiptController;
class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $withdraw = Withdraw::orderBy('created_at', 'DESC')->get();
        return view('member.withdraws', compact('withdraw'));
    }

    public function myindex()
    {
        //
        $withdraw = Withdraw::orderBy('created_at', 'DESC')->where('user_id', \Auth::user()->id)->get();
        return view('member.mywithdraws', compact('withdraw'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(\Auth::user()->id);
        $missing = array();
        if ($user->firstname == "") $missing[] = "Firstname";
        if ($user->lastname == "") $missing[] = "Lastname";
        if ($user->paypal_email == "") $missing[] = "PayPal Email";
        if ($user->street == "") $missing[] = "Street";
        if ($user->street_number == "") $missing[] = "Street Number";
        if ($user->zip == "") $missing[] = "Zip";
        if ($user->city == "") $missing[] = "City";
        if ($user->city == "") $missing[] = "Country";

        return view('member.withdraw-request')->with('missing', $missing);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'paypal_email' => 'required|email',
            'amount' => 'required|integer|min:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error_message', $validator->errors());
        }

        $user = User::find(\Auth::user()->id);
        if ($data['amount'] > 0 && $user->balance_seller >= 50 && $user->balance_seller >= $data['amount']) {
            $check_record = Withdraw::where([['user_id', '=', \Auth::user()->id], ['status', '=', '0']])->count();
            if ($check_record == 0) {
                $transaction = Withdraw::create([
                    'user_id' => \Auth::user()->id,
                    'amount' => $data['amount'],
                    'status' => 0,
                    'paypal_email' => $data['paypal_email']
                ]);
                if ($transaction) {
                    $seller_balance_before = $user->balance_seller;
                    $user->balance_seller = $user->balance_seller - $data['amount'];
                    $user->update();
                    $params['subject'] = "New Withdrawal Request: " . $data['amount'] . "€";
                    $params['message'] = "UserID: " . $user->id . "<br>Email: " . $user->email . "<br>Seller Balance now: " . $user->balance_seller . "<br>Seller Balance before: " . $seller_balance_before . "<br><a href='" . route('withdrawlist') . "'>" . route('withdrawlist') . "</a>";
                    Mail::to(config('app.admin_email'))->send(new MailAdminNotification($params));
                }
                return redirect()->back()->with('message', 'Your request was successfully submitted.');
            } else {
                $validator->errors()->add('', 'Your previous request is already pending for approval.');
                return redirect()->back()->with('error_message', $validator->errors());

            }
        } else {
            $validator->errors()->add('amount', 'The requested amount exceeds your balance.');
            return redirect()->back()->with('error_message', $validator->errors());
        }
    }

    public function declinewitdraw($id, Request $request)
    {
        if(isset($id))
        {
            $withdraw = Withdraw::where([['id','=',$id]])->first();
            $withdraw->status = 2;
            $withdraw->update();
            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function approvewitdraw($id, Request $request)
    {
        if(isset($id)) {
            $withdraw = Withdraw::where([['id', '=', $id]])->first();
            $user_id = $withdraw->user_id;
            $amount = $withdraw->amount;
            $user_withdraw_email = $withdraw->paypal_email;
            $user = User::where([['id', '=', $user_id]])->first();
            if ($user->country == "DE" && $user->kleinunternehmer == 0 && trim($user->vat)) {
                $amount = round($amount * 1.19, 2);
            }
            $user_email = $user->email;
            $clientSecret = env('PAYPAL_SECRET_KEY');
            $paypal_url = env('PAYPAL_URL');
            $clientId = env('PAYPAL_CLIENT_ID');
            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);
            $request = new PayoutsPostRequest();
            $body = json_decode(
                '{
                    "sender_batch_header":
                    {
                      "email_subject": "You have a payout!"
                    },
                    "items": [
                    {
                      "recipient_type": "EMAIL",
                      "receiver": "' . $user_withdraw_email . '",
                      "note": "Credit Payout (ID:' . $withdraw->id . ') of ' . $amount . \Config::get('app.currency_name') . '",
                      "sender_item_id": "'.$withdraw->id.'",
                      "amount":
                      {
                        "currency": "'.\Config::get('app.currency_name').'",
                        "value": "'.$amount.'"
                      }
                    }]
                  }',
                true);

            //$client = PayPalHttpClient::client();
            try{
                $request->body = $body;
                $response = $client->execute($request);
                if($response->statusCode == 201) {
                    // $user = User::where([['id','=',$user_id]])->first();
                    // $new_balance = $user->balance_seller - $amount;
                    // $user->balance_seller = $new_balance;
                    // if($new_balance >= 0) {
                    $withdraw->reference_id = $response->result->batch_header->payout_batch_id;
                    $withdraw->status = 1;
                    $withdraw->payout_at = now();
                    $withdraw->amount_withdrawn = $amount;
                    $withdraw->update();
                    // $user->update();
                    $transaction = UserBalanceHistory::create([
                        'user_id' => $withdraw->user_id,
                        'reference_type' => \Config::get('constants.reference_type.withdraw_approval'),
                        'amount' => $amount,
                        'order_id' => $withdraw->id,
                        'history_description' => 'Approved Withdrawal Request - WithdrawID: ' . $withdraw->id,
                        'balance_type' => \Config::get('constants.balance_type.debit'),
                        'balance_account' => \Config::get('constants.balance_account.seller')

                    ]);
                    $params['user_fullname'] = $user->firstname . " " . $user->lastname;
                    $params['amount'] = $amount;
                    $params['paypal_email'] = $user_withdraw_email;
                    // create invoice here to avoid user can change tax status in timeframe between payment and invoive creation.
                    $create_receipt = new ReceiptController();
                    $create_receipt->index($id, $user_id);
                    Mail::to($user->email)->send(new WithdrawalApprovedSellerNotification($params));
                    return response()->json(['status' => 'success', 'message' => 'Request Approved'], 200);

                    $notification = new Notification();
                    $notification->user_id = $OrdersDetail->seller_id;
                    $notification->type ="You received a withdrawal of ".\Config::get('app.currency_name').$amount." to your PayPal account";
                    $notification->save();
                    //  }
                    // else{
                    //     return response()->json(['status'=>'error','message' => 'There is some problem'], 200);
                    // }
                }

            }
             catch(\PayPalHttp\HttpException $e){
                //echo $e->getMessage();
                return response()->json(['status'=>'error','message' => $e->getMessage()], 200);
                //var_dump(json_decode($e->getMessage()));

            }



        }
        return response()->json(['status'=>'error', 'message' => 'Something went wrong!'], 422);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }

    public function temp()
    {
        /*echo \Config::get('constants.reference_type.withdraw_approval');
        exit;*/
        /*For Change Custom field*/
        $data['subscriptionId'] = 2471664;
        $data['vendorAccountId'] = 160836;
        $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
        $data['customFields']['x-orderid'] = 300;
        $data['customFields']['x-pk_user'] = 13;
        $client = new \GuzzleHttp\Client(['verify' => false ]);
        try{
            $response=$client->post("https://store.payproglobal.com/api/Subscriptions/ChangeCustomFields",array(
                \GuzzleHttp\RequestOptions::JSON => $data
            ));
            echo "<pre>1";
            print_r(json_decode($response->getBody(),true));exit;
          }
          catch(\GuzzleHttp\Exception\ClientException $e)
          {
            echo "<pre>2";
              var_dump($e->getMessage());exit;

          }
        /*For Subscription Charge*/
        /*$data['subscriptionId'] = 2470856;
        $data['vendorAccountId'] = 160836;
        $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
        $client = new \GuzzleHttp\Client(['verify' => false ]);
        try{
            $response=$client->post("https://store.payproglobal.com/api/Subscriptions/DoRecurringPayment",array(
                \GuzzleHttp\RequestOptions::JSON => $data
            ));
            echo "<pre>1";
            print_r(json_decode($response->getBody(),true));exit;
          }
          catch(\GuzzleHttp\Exception\ClientException $e)
          {
            echo "<pre>2";
              var_dump($e->getMessage());exit;

          }*/
          /*Chnage Recurring Price*/
            /*$data['newPriceValue'] = "3.00";
            $data['priceCurrencyCode'] = "INR";
            $data['quantity'] = 1;
            $data['newSubscriptionName'] = "Monthly premium subscription plan plus 5 extra packages";
            $data['sendCustomerNotification'] = false;
            $data['subscriptionId'] = 2443429;
            $data['vendorAccountId'] = 160836;
            $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            try{
                $response=$client->post("https://store.payproglobal.com/api/Subscriptions/ChangeRecurringPrice",array(
                    \GuzzleHttp\RequestOptions::JSON => $data
                ));
                echo "<pre>1";
                print_r(json_decode($response->getBody(),true));exit;
            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                echo "<pre>2";
                print_r($e->getResponse()->getBody()->getContents());exit;

            }*/
            /*$time = strtotime(date("Y-m-d H:i:s"));
            $final = date("Y-m-d", strtotime("+1 month", $time));
            $data['newNextPaymentDate'] = $final;
            $data['shiftPaymentSchedule'] = true;
            $data['newSubscriptionName'] = "Monthly premium subscription plan plus 5 extra packages";
            $data['sendCustomerNotification'] = true;
            $data['subscriptionId'] = 2443429;
            $data['vendorAccountId'] = 160836;
            $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            try{
                $response=$client->post("https://store.payproglobal.com/api/Subscriptions/ChangeNextPaymentDate",array(
                    \GuzzleHttp\RequestOptions::JSON => $data
                ));
                echo "<pre>1";
                print_r(json_decode($response->getBody(),true));exit;
            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                echo "<pre>2";
                print_r($e->getResponse()->getBody()->getContents());

            }*/
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            $rec_data= array();
            $rec_data['newNextPaymentDate'] = date('Y-m-d',strtotime('first day of next month'));
            $rec_data['shiftPaymentSchedule'] = true;
            $rec_data['newSubscriptionName'] = "Monthly premium subscription";
            $rec_data['sendCustomerNotification'] = true;
            $rec_data['subscriptionId'] = 2470856;
            $rec_data['vendorAccountId'] = env('VENDOR_ACCOUNT_ID');
            $rec_data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $result_url = env('PAYPRO_URL').'api/Subscriptions/ChangeNextPaymentDate';
            try{
              $response=$client->post($result_url,array(
                  \GuzzleHttp\RequestOptions::JSON => $rec_data
              ));
              //echo "<pre>1";
              //print_r(json_decode($response->getBody(),true));exit;
              Log::info('Next Payment date update for success: 2470856'.$response->getBody());

            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                //echo "<pre>2";
                Log::info('Next Payment date update for fail: 2470856 :'.json_encode($e->getResponse()->getBody()->getContents()));
                //print_r($e->getResponse()->getBody()->getContents());exit;

            }
        return view('member.temp');
    }
}
