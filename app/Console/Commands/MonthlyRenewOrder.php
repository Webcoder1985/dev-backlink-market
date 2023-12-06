<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PayproLog;
use App\Models\Links;
use Illuminate\Support\Facades\Log;
use Storage;

class MonthlyRenewOrder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:monthlyreneworder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //$start_date = date('Y-m-01');
        $start_date = date('Y-m-d');
        $order_users = Links::select('buyer_id')->where('buyer_paid_until', '=', $start_date)->where('status', '=', '20')->groupBy('buyer_id')->get();
        foreach($order_users as $order_user)
        {
            $orders = Links::where('buyer_paid_until', '=', $start_date)->where('status', '=', '20')->where('buyer_id', '=', $order_user->buyer_id)->get();
            $order_amount = 0;
            foreach($orders as $order)
            {
                //echo $order->page->page_price_seller;exit;
                $order_amount = $order_amount + $order->page->page_price_buyer;
            }
            $user = User::where('id', '=', $order_user->buyer_id)->first();
            $tax=$order_amount*config('cart.tax')/100;
            $order_amount = $order_amount + $tax;
            $ordercreate = Order::create([
                'pay_sn' => 0,
                'user_id' => $order_user->buyer_id,
                'user_full_name' => $user->firstname." ".$user->lastname,
                'user_email' => $user->email,
                'order_amount' => $order_amount,
                'order_status' => 10,
                'order_currency' =>\Config::get('app.currency_name')
            ]);
            foreach($orders as $order)
            {
                $order_detail = OrderDetail::create([
                    'status' => 10,
                    'buyer_id' => $order_user->buyer_id,
                    'order_id' => $ordercreate->id,
                    'seller_id' => $order->seller_id,
                    'seller_full_name' => $order->seller_full_name,
                    'seller_email' => $order->seller_email,
                    'page_id' => $order->page_id,
                    'page_url' => $order->page_url,
                    'page_price_buyer' => $order->page->page_price_buyer,
                    'page_price_seller' => $order->page->page_price_seller,
                    'promoted_url' => $order->promoted_url,
                    'link_content' => $order->link_content,
                    'anchor_text' => $order->anchor_text,
                    'no_follow' => $order->no_follow,
                    'blmkt_links_id' => $order->id
                ]);
            }
            $data['newPriceValue'] = $order_amount;
            $data['priceCurrencyCode'] = \Config::get('app.currency_name');
            $data['quantity'] = 1;
            $data['newSubscriptionName'] = "Monthly premium subscription";
            $data['sendCustomerNotification'] = false;
            $data['subscriptionId'] = $user->paypro_subscription_id;
            $data['vendorAccountId'] =  env('VENDOR_ACCOUNT_ID');
            $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $result_url = env('PAYPRO_URL').'api/Subscriptions/ChangeRecurringPrice';
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            try{
                $response=$client->post($result_url,array(
                    \GuzzleHttp\RequestOptions::JSON => $data
                ));
                Log::info('Change Recurring Price for success'.$data['subscriptionId'].' : '.$response->getBody());
            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                Log::info('Change Recurring Price for fail'.$data['subscriptionId'].' : '.json_encode($e->getResponse()->getBody()->getContents()));

            }


            $data['customFields']['x-orderid'] = $ordercreate->id;
            $data['customFields']['x-pk_user'] = $order_user->buyer_id;
            $data['subscriptionId'] = $user->paypro_subscription_id;
            $data['vendorAccountId'] =  env('VENDOR_ACCOUNT_ID');
            $data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $result_url = env('PAYPRO_URL').'api/Subscriptions/ChangeCustomFields';
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            try{
                $response=$client->post($result_url,array(
                    \GuzzleHttp\RequestOptions::JSON => $data
                ));
                Log::info('Change ChangeCustomFields for success '.$data['subscriptionId'].' : '.$response->getBody());
            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                Log::info('Change ChangeCustomFields for fail '.$data['subscriptionId'].' : '.json_encode($e->getResponse()->getBody()->getContents()));

            }

            $data_new['subscriptionId'] =  $user->paypro_subscription_id;
            $data_new['vendorAccountId'] = env('VENDOR_ACCOUNT_ID');
            $data_new['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
            $result_url = env('PAYPRO_URL').'api/Subscriptions/DoRecurringPayment';
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            try{
                $response=$client->post($result_url,array(
                    \GuzzleHttp\RequestOptions::JSON => $data_new
                ));

                Log::info('Do Recurring Payment for success'.$data['subscriptionId'].' : '.$response->getBody());
            }
            catch(\GuzzleHttp\Exception\ClientException $e)
            {
                Log::info('Do Recurring Payment for fail'.$data['subscriptionId'].' : '.json_encode($e->getResponse()->getBody()->getContents()));

            }
        }
        return 0;
    }
}
