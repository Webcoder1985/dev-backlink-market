<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PayproLog;
use App\Models\Links;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\MailAdminNotification;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
  public function saveOrder(Request $request)
  {
      $data = $request->all();

      $total_days=cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
      $left_days=$total_days-date('d');
        $subtotal=\Cart::subtotal(2);
      $current_price=(\Cart::subtotal(2)/$total_days)*$left_days;
      //$current_price*tax
      $tax=$current_price*config('cart.tax')/100;
      //echo config('cart.tax')."-TEST";exit;
      $current_price+=$tax;
      $current_price=round($current_price, 4,PHP_ROUND_HALF_UP);
      $current_price=round($current_price, 3,PHP_ROUND_HALF_UP);
      $current_price=round($current_price, 2,PHP_ROUND_HALF_UP);
      //echo $current_price."-TEST";exit;

      $order = Order::create([
          'pay_sn' => 0,
          'user_id' => \Auth::user()->id,
          'user_full_name' => \Auth::user()->firstname. " ".\Auth::user()->lastname,
          'user_email' => \Auth::user()->email,
          'order_amount' => $current_price,//\Cart::subtotal(),
          'order_currency' => \Config::get('app.currency_name'),
          'order_status' => 10
      ]);
      $order_id = $order->id;
      $products_ids = $data['product_id'];
      $m=0;
      $product_params="";
      $aes = new \Crypt_AES();
      $aes->setKey(env('PAYPRO_KEY'));
      $aes->setIV(env('PAYPRO_IV'));
      $aes->setKeyLength(256);

      if(\Auth::user()->paypro_subscription_id!=""){
         $dynamic_product_id = env('PAYPRO_NORMAL_PRODUCT_ID');
      }
      else
        $dynamic_product_id = env('PAYPRO_SUBSCRIPTION_PRODUCT_ID');

      foreach($products_ids as $products_id)
      {
          $page = Page::where('id', $products_id)->first();
          $seller = User::find($page['seller_id']);
          $seller_site = SellerSite::where('id', '=', $page->seller_site_id)->first();
          $pr_current_price=($page->page_price_buyer/$total_days)*$left_days;
          $pr_current_price=round($pr_current_price,2);
          //Seller Price Calculation
          $pr_current_page_price_seller=($page->page_price_seller/$total_days)*$left_days;
          $pr_current_page_price_seller=round($pr_current_page_price_seller,2);
          $order = OrderDetail::create([
              'order_id' => $order_id,
              'buyer_id' => \Auth::user()->id,
              'seller_id' => $page->seller_id,
              'seller_full_name' => $seller->firstname. " ".$seller->lastname,
              'seller_email' => $seller->email,
              'page_id' => $products_id,
              'page_url' => $page->seller_site_page_url,
              'page_price_seller' => $pr_current_page_price_seller,
              'page_price_buyer' => $pr_current_price,
              'promoted_url' => $data['promoted_url'][$m],
              'link_content' => $data['link_content'][$m],
              'anchor_text' => $data['anchor_text'][$m],
              'no_follow' => $data['no_follow'][$m],
          ]);

          $data_payload = array();
          $data_payload['Name'] = $page->seller_site_page_url;//'Credit Load Subscription '.$order_id;
          $data_payload['Description']=\Config::get('app.currency_symbol').' '.$pr_current_price.' this month and then '.\Config::get('app.currency_symbol').' '.$page->page_price_buyer.' / month';
          $data_payload['qty']= 1;
          $data_payload['Price']['EUR']['Amount'] = $pr_current_price;
          $data_payload['RecurringPrice']['EUR']['Amount'] = $pr_current_price;
          $data_payload['x-pk_user'] = \Auth::user()->id;
          $data_payload['x-orderid'] = $order_id;
          $data_str = http_build_query($data_payload);
          $data_final = urlencode(base64_encode($aes->encrypt($data_str)));

          $product_params .= "products[" . ($m + 1) . "][data]=" . $data_final . "&products[" . ($m + 1) . "][id]=" . $dynamic_product_id . "&products[" . ($m + 1) . "][allow-recurring-charges]=true&";

          $m++;
      }


      //print_r($product_params);
      //exit;


      //$result_url = env('PAYPRO_URL')."checkout?currency=EUR&products[1][allow-recurring-charges]=true&products[1][id]=".$dynamic_product_id."&billing-first-name=".\Auth::user()->firstname."&billing-last-name=".\Auth::user()->lastname."&billing-email=".urldecode(\Auth::user()->email)."&payment-method=14&products[1][data]=".$data_final."&enable-auto-renewal=true&use-test-mode=true&secret-key=".env('PAYPRO_SECRET_KEY)";
      if (env('PAYPRO_TESTMODE')) {
          $result_url = env('PAYPRO_URL') . "checkout?currency=EUR&billing-first-name=" . \Auth::user()->firstname . "&billing-last-name=" . \Auth::user()->lastname . "&billing-email=" . urldecode(\Auth::user()->email) . "&payment-method=14&" . $product_params . "enable-auto-renewal=true&use-test-mode=true&secret-key=" . env('PAYPRO_SECRET_KEY');

      } else {
          $result_url = env('PAYPRO_URL') . "checkout?currency=EUR&billing-first-name=" . \Auth::user()->firstname . "&billing-last-name=" . \Auth::user()->lastname . "&billing-email=" . urldecode(\Auth::user()->email) . "&payment-method=14&" . $product_params . "enable-auto-renewal=true&secret-key=" . env('PAYPRO_SECRET_KEY');

      }

      //header('Access-Control-Allow-Origin:  *');
      //header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
      //header('Access-Control-Allow-Methods:  POST, PUT');
      //header("Location: ".$result_url);
	    //exit();



      //exit;
      //\Cart::destroy();
      return response()->json(['status' => 'success', 'product' => $order,'redirect_url'=>$result_url], 200);



  }

  public function thankYouOrder(Request $request){
    $data = $request->all();

      \Cart::destroy();
    return view('member.thank-you-order');

  }

  public function payproIPN(Request $request){

    $data = json_decode('{"ORDER_ID":"27020714","PRODUCT_ID":"75730","ORDER_REFERRER_URL":"https:\/\/public.test\/","ORDER_STATUS":"Processed","ORDER_STATUS_ID":"5","CUSTOMER_NAME":"John Doe","CUSTOMER_FIRST_NAME":"John","CUSTOMER_LAST_NAME":"Doe","CUSTOMER_STREET_NUMBER":null,"CUSTOMER_FISCAL_NUMBER_PERSONAL":null,"CUSTOMER_FISCAL_NUMBER_CORPORATE":null,"CHECKOUT_QUERY_STRING":"currency=EUR&billing-first-name=&billing-last-name=&billing-email=user%40backlink-market.com&payment-method=14&products%5b1%5d%5bdata%5d=BjiLJMjLAWTZuIteRRrawn2Q3oqpIIshY39OT8cjnX6eJYYhVMgy9taxacNAJCAyvUmovXSjXlTPelijsZjfNCMwDc9SoS5KRoH28JUmrAuy1UghFE%2bFKNfBXjjhecXvl5ZeyL59JcBmt8KsSznB4yAGSqUfdnC4slON0SAqif7cPTNzvqG7vQj2ReBnShWgQzmRwa976cvYPTMvKNS%2fEitaeYjelErwCtngrsIRkBcYOzrGo2IvEErxiwCYGPJgwsYMoL4MmeN4bZO705M5gZxCosyr1yjOhh6rahDHejzJs6%2fD2%2foi%2fPVZBsz5wEul&products%5b1%5d%5bid%5d=75730&products%5b1%5d%5ballow-recurring-charges%5d=true&products%5b2%5d%5bdata%5d=BjiLJMjLAWTZuIteRRrawn2Q3oqpIIshY39OT8cjnX6eJYYhVMgy9taxacNAJCAyvUmovXSjXlTPelijsZjfNCMwDc9SoS5KRoH28JUmrAuy1UghFE%2bFKNfBXjjhecXvl5ZeyL59JcBmt8KsSznB4yAGSqUfdnC4slON0SAqif7cPTNzvqG7vQj2ReBnShWgQzmRwa976cvYPTMvKNS%2fEitaeYjelErwCtngrsIRkBcYOzrGo2IvEErxiwCYGPJgwsYMoL4MmeN4bZO705M5gZxCosyr1yjOhh6rahDHejzJs6%2fD2%2foi%2fPVZBsz5wEul&products%5b2%5d%5bid%5d=75730&products%5b2%5d%5ballow-recurring-charges%5d=true&enable-auto-renewal=true&use-test-mode=true&secret-key=-K%246Taqxco","CROSS_SELL_MAIN_ITEM_ID":null,"CROSS_SELL_ITEM_IDS":null,"IS_CROSS_SELL_ITEM":"False","CUSTOMER_EMAIL":"user@backlink-market.com","CUSTOMER_COUNTRY_NAME":"Germany","CUSTOMER_STATE_NAME":null,"EC_PRODUCT_ID":null,"PRODUCT_COMBINATION_ID":null,"PRODUCT_COMBINATION_NAME":null,"OPTION_GROUP_IDS":null,"OPTION_GROUP_ITEM_IDS":null,"SELECTION_GROUP_ITEM_IDS":null,"SELECTION_GROUP_ITEM_NAMES":null,"PRODUCT_QUANTITY":"1","ORDER_ITEM_ID":"35239119","ORDER_ITEM_NAME":"https:\/\/wordpress.test\/hello-world\/","ORDER_ITEM_TYPE_ID":"1","ORDER_ITEM_TYPE_NAME":"Product","ORDER_ITEM_SKU":null,"ORDER_CURRENCY_CODE":"EUR","ORDER_ITEM_VENDOR_AMOUNT":"0.00","ORDER_ITEM_PRICE":"9.68","ORDER_ITEM_UNIT_PRICE":"9.68","ORDER_ITEM_TOTAL_AMOUNT":"9.68","ORDER_ITEM_AFFILIATE_AMOUNT":"0.00","ORDER_ITEM_PARTNERS_AMOUNT":"0.00","ORDER_ITEM_PAYPRO_EXPENSES_AMOUNT":"0.00","ORDER_TOTAL_AMOUNT":"0.00","ORDER_TAXES_AMOUNT":"0.00","ORDER_ITEM_COUPON_DISCOUNT":"0.00","ORDER_ITEM_DYNAMIC_DISCOUNT":"0.00","ORDER_ITEM_LEAD_DISCOUNT":"0.00","ORDER_ITEM_PROMO_DISCOUNT":"0.00","ORDER_ITEM_VOLUME_DISCOUNT":"0.00","ORDER_ITEM_TOTAL_DISCOUNT":"0.00","ORDER_TOTAL_AMOUNT_SHOWN":"19.36","VENDOR_BALANCE_CURRENCY_CODE":"USD","ORDER_ITEM_BALANCE_CURRENCY_VENDOR_AMOUNT":"0.00","ORDER_ITEM_BALANCE_CURRENCY_TOTAL_AMOUNT":"0.00","ORDER_ITEM_BALANCE_CURRENCY_AFFILIATE_AMOUNT":"0.00","ORDER_ITEM_BALANCE_CURRENCY_PARTNERS_AMOUNT":"0.00","ORDER_ITEM_BALANCE_CURRENCY_PAYPRO_AMOUNT":"0.00","ORDER_ITEM_BALANCE_CURRENCY_PAYPRO_EXPENSES_AMOUNT":"0.00","ORDER_TOTAL_BALANCE_CURRENCY_AMOUNT":"0.00","ORDER_TAXES_BALANCE_CURRENCY_AMOUNT":"0.00","ORDER_BALANCE_CURRENCY_VENDOR_AMOUNT":"0.00","ORDER_ITEM_REFUNDED":"0.00","ORDER_ITEM_VENDOR_REFUNDED":"0.00","ORDER_ITEM_AFFILIATE_REFUNDED":"0.00","ORDER_ITEM_PARTNERS_REFUNDED":"0.00","ORDER_ITEM_BALANCE_CURRENCY_REFUNDED":"0.00","ORDER_ITEM_BALANCE_CURRENCY_VENDOR_REFUNDED":"0.00","ORDER_ITEM_BALANCE_CURRENCY_AFFILIATE_REFUNDED":"0.00","ORDER_ITEM_BALANCE_CURRENCY_PARTNERS_REFUNDED":"0.00","ORDER_REFUNDED":"0.00","ORDER_VENDOR_REFUNDED":"0.00","ORDER_AFFILIATE_REFUNDED":"0.00","ORDER_PARTNERS_REFUNDED":"0.00","ORDER_BALANCE_CURRENCY_REFUNDED":"0.00","ORDER_BALANCE_CURRENCY_VENDOR_REFUNDED":"0.00","ORDER_BALANCE_CURRENCY_AFFILIATE_REFUNDED":"0.00","ORDER_BALANCE_CURRENCY_PARTNERS_REFUNDED":"0.00","ORDER_ITEM_TAX_NAME_1":"USt","ORDER_ITEM_TAX_RATE_1":"19.00","PAYMENT_METHOD_ID":"14","PAYMENT_METHOD_NAME":"PayPal","ORDER_PLACED_TIME_UTC":"01\/16\/2023 07:33:17","ORDER_PLACED_TIME_CUSTOMER_TIMEZONE":"01\/16\/2023 08:33:13","CUSTOMER_TIMEZONE":null,"CUSTOMER_ID":"15483517","COMPANY_NAME":null,"CUSTOMER_NAME_ASCII":"John Doe","CUSTOMER_FIRST_NAME_ASCII":"John","CUSTOMER_LAST_NAME_ASCII":"Doe","CUSTOMER_TITLE":null,"CUSTOMER_IP":"89.56.57.47","LICENSED_TO_NAME":null,"LICENSED_TO_NAME_ASCII":null,"LICENSED_TO_EMAIL":null,"COUPON_NAME":null,"CUSTOMER_COUNTRY_CODE_BY_IP":"DE","CUSTOMER_COUNTRY_NAME_BY_IP":"Germany","CUSTOMER_COUNTRY_CODE":"DE","CUSTOMER_PHONE":"+1-001-12345679","CUSTOMER_LANGUAGE_CODE":"de","CUSTOMER_STATE_CODE":null,"CUSTOMER_CITY":"Kiel","CUSTOMER_STREET_ADDRESS":"17 test street name","CUSTOMER_ZIPCODE":"24103","SHIPPING_FIRST_NAME":null,"SHIPPING_LAST_NAME":null,"SHIPPING_FIRST_NAME_ASCII":null,"SHIPPING_LAST_NAME_ASCII":null,"SHIPPING_COUNTRY_CODE":null,"SHIPPING_COUNTRY_NAME":null,"SHIPPING_STATE_CODE":null,"SHIPPING_STATE_NAME":null,"SHIPPING_CITY":null,"SHIPPING_STREET_ADDRESS":null,"SHIPPING_ZIPCODE":null,"ORDER_CUSTOM_FIELDS":"x-pk_user=2,x-orderid=5","CORPORATE_PURCHASE":"False","CUSTOM_LICENSE_INFO":null,"SUBSCRIPTION_ID":"2871398","EC_SUBSCRIPTION_ID":null,"SUBSCRIPTION_STATUS_ID":"1","SUBSCRIPTION_STATUS_NAME":"Active","SUBSCRIPTION_NEXT_CHARGE_DATE":"2\/16\/2023 7:33 AM","SUBSCRIPTION_NEXT_CHARGE_AMOUNT":"9.68","SUBSCRIPTION_NEXT_CHARGE_CURRENCY_CODE":"EUR","SUBSCRIPTION_NEXT_CHARGE_CURRENCY":"1","SUBSCRIPTION_NUMBER_OF_BILLING_CYCLES":"1","SUBSCRIPTION_RENEWAL_TYPE":"Auto","SUBSCRIPTION_INITIAL_ORDER_ID":"27020714","SUBSCRIPTION_INITIAL_EC_ORDER_ID":null,"IS_ON_TRIAL_PERIOD":"0","TRIAL_PERIOD_TILL":null,"ACTION_REASON":null,"CREDITCARD_LAST4":null,"PAYPAL_ACCOUNT":"berndmeier@sdfdsf.com","IS_DELAYED_PAYMENT":"0","COUPON_ID":null,"COUPON_CODE":null,"AFFILIATE_AGREEMENT_ID":null,"AFFILIATE_NETWORK_ID":null,"AFFILIATE_VENDOR_ACCOUNT_ID":null,"IPN_TYPE_ID":"1","IPN_TYPE_NAME":"OrderCharged","PAYPRO_GLOBAL":"1","CLOUD_COMMERCE":"1","TEST_MODE":"1","HASH":"c4ca4238a0b923820dcc509a6f75849b","SIGNATURE":"3e61b3a24c292a33a16991b6f00109fed890633e9bbfadb81ad7bde2088e001b","ORDER_ITEM_LICENSES":null,"ORDER_ITEMS_COUNT":"2","BUNDLED_ITEMS_COUNT":"0","REGIONAL_PRICE":"False","INVOICE_LINK":"https:\/\/store.payproglobal.com\/Invoice?Id=1c9de7e2-38cb-454b-aefa-493c2c0ca171&Date=20230116","CREDIT_CARD_BIN":null,"CREDIT_CARD_LAST4":null,"CREDIT_CARD_EXPIRATION_DATE":null,"CREDIT_CARD_BIN_RESULT":null,"MAXMIND_RESULT":null,"ORDER_TOTAL_AMOUNT_WITH_TAXES_SHOWN":"23.04"}',true);
    //$request->all();
    if(!in_array($_SERVER['REMOTE_ADDR'],array('127.0.0.1','52.112.95.38','198.199.123.239','157.230.8.40','2604:a880:400:d0::1843:7001','2604:a880:400:d1::b6c:c001'))) {
      Log::info('Fraud IPN Request: '.json_encode($_SERVER));
    }
    else{
      $payProIpn=PayproLog::where('paypro_order_id','=',$data['ORDER_ID'])->first();
      if(!$payProIpn){
        Log::info('First Time IPN: '.json_encode($data));
        $data=array_change_key_case($data);
        $data['paypro_order_id']=$data['order_id'];
        //$data["order_custom_fields"]=urldecode($data["order_custom_fields"]);
        $asArr = explode( ',', $data["order_custom_fields"] );

        foreach( $asArr as $val ){
          $tmp = explode( '=', $val );
          $finalArray[ $tmp[0] ] = $tmp[1];
        }
        $pk_user= $finalArray["x-pk_user"];
        $order_id = $finalArray["x-orderid"];

        $data['order_id']=$order_id;
        $data['pk_user']=$pk_user;
        $user=User::where('id',$pk_user)->first();
        if($user && $user->paypro_subscription_id==""){
          $user->paypro_subscription_id=$data['subscription_id'];
          $user->update();
        }

        //print_r($data);
        $payprolog = PayproLog::create($data);


        if($data['ipn_type_id']==6 || $data['ipn_type_id']==1 || $data['ipn_type_id']==9) {
          if ($data['order_status_id'] == 5) {
            $order_details=OrderDetail::where('order_id',$order_id)->get();
            foreach ($order_details as $order_detail) {
              if($order_detail->blmkt_links_id == '')
              {
                $notification = new Notification();
                $notification->user_id=$order_detail->seller_id;
                $notification->type="Order received for link ".$order_detail->page_url;
                $notification->save();
                $links['status'] = 20;
                $links['buyer_id'] = $order_detail->buyer_id;
                $links['seller_id'] = $order_detail->seller_id;
                $links['seller_full_name'] = $order_detail->seller_full_name;
                $links['seller_email'] = $order_detail->seller_email;
                $links['page_id'] = $order_detail->page_id;
                $links['page_url'] = $order_detail->page_url;
                $links['page_price_buyer'] = $order_detail->page_price_buyer;
                $links['page_price_seller'] = $order_detail->page_price_seller;
                $links['promoted_url'] = $order_detail->promoted_url;
                $links['link_content'] = $order_detail->link_content;
                $links['anchor_text'] = $order_detail->anchor_text;
                $links['no_follow'] = $order_detail->no_follow;
                $links['buyer_paid_until'] = date('Y-m-d',strtotime('first day of next month'));
                //$links['buyer_paid_until'] = '2021-12-09';
                $createlinks = Links::create($links);
                $orderdetailsave=OrderDetail::where('id',$order_detail->id)->first();
                $orderdetailsave->blmkt_links_id = $createlinks->id;
                $orderdetailsave->update();
              }
              else{
                $notification = new Notification();
                $notification->user_id=$order_detail->seller_id;
                $notification->type="Order received for link ".$order_detail->page_url;
                $notification->save();
                $linkssave=Links::where('id',$order_detail->blmkt_links_id)->first();
                $olddetail=OrderDetail::where('blmkt_links_id',$order_detail->blmkt_links_id)->where('id','!=',$order_detail->id)->latest('created_at')->first();
                $linkssave->status = 20;
                $linkssave->last_order_id = $olddetail->order_id;
                $linkssave->buyer_paid_until = date('Y-m-d',strtotime('first day of next month'));
                $linkssave->page_price_buyer = $order_detail->page_price_buyer;
                $linkssave->page_price_seller = $order_detail->page_price_seller;
                $linkssave->update();
              }
                if (add_links_seller_blog($order_detail->page_id)!="success"){
                    $params['subject'] = "Error on add_link_seller_blog w. page_id: " . $order_detail->page_id;
                    $params['message'] = "Order_Controller.php L.221 Failed adding Links after Payment. Order Details id: ".$order_detail->id."\r\n"."Check Page: ".$order_detail->page_url." for link: ".$order_detail->promoted_url." with anchor: ".$order_detail->anchor_text ;
                    Mail::to(config('app.admin_email'))->send(new MailAdminNotification($params));
                }


            }
            $order=Order::where('id',$order_id)->first();
            if($order){
              $order->order_status=20;
              $order->paypro_order_id = $data['paypro_order_id'];
              $order->paypro_subscription_id = $data['subscription_id'];
              $order->ipn_log_paypro_id = $payprolog->id;
              $order->update();
              if($data['subscription_id'] != ''){
                //Change next schedule payment date
                $client = new \GuzzleHttp\Client(['verify' => false ]);
                  $rec_data= array();
                  $rec_data['newNextPaymentDate'] = date('Y-m-d',strtotime('first day of next month'));
                  //$rec_data['newNextPaymentDate'] = '2021-12-09';
                  $rec_data['shiftPaymentSchedule'] = true;
                  $rec_data['newSubscriptionName'] = "Monthly premium subscription";
                  $rec_data['sendCustomerNotification'] = true;
                  $rec_data['subscriptionId'] = $data['subscription_id'];
                  $rec_data['vendorAccountId'] = env('VENDOR_ACCOUNT_ID');
                  $rec_data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
                  $result_url = env('PAYPRO_URL').'api/Subscriptions/ChangeNextPaymentDate';
                  try{
                    $response=$client->post($result_url,array(
                        \GuzzleHttp\RequestOptions::JSON => $rec_data
                    ));
                    //Log::info('Next Payment For: '.$data['subscription_id'].':'.$response->getBody());
                    Log::info('Next Payment date update for success'.$data['subscription_id'].' : '.$response->getBody());

                  }
                  catch(\GuzzleHttp\Exception\ClientException $e)
                  {
                    Log::info('Next Payment date update for fail'.$data['subscription_id'].' : '.json_encode($e->getResponse()->getBody()->getContents()));

                  }

                  //Change subscription renewal type
                  $client = new \GuzzleHttp\Client(['verify' => false ]);
                  $rec_data= array();
                  $rec_data['renewalTypeId'] = 2;
                  $rec_data['subscriptionId'] = $data['subscription_id'];
                  $rec_data['vendorAccountId'] = env('VENDOR_ACCOUNT_ID');
                  $rec_data['apiSecretKey'] = env('PAYPRO_SECRET_KEY');
                  $result_url = env('PAYPRO_URL').'api/Subscriptions/ChangeRenewalType';
                  try{
                    $response=$client->post($result_url,array(
                        \GuzzleHttp\RequestOptions::JSON => $rec_data
                    ));
                    //Log::info('Next Payment For: '.$data['subscription_id'].':'.$response->getBody());
                    Log::info('Subscription Renewal Mode Changed success for ' . $data['subscription_id'] . ' : ' . $response->getBody());

                  }
                  catch(\GuzzleHttp\Exception\ClientException $e)
                  {
                    Log::info('Subscription Renewal Mode Changed fail for '.$data['subscription_id'].' : '.json_encode($e->getResponse()->getBody()->getContents()));

                  }
              }
            }
          }
          else{
            $order=Order::where('id',$order_id)->first();
            if($order){
              $order->order_status=0;
              $order->update();
            }
          }
        }
      }
      else{
          Log::info('Duplicate IPN: ' . json_encode($data));
      }
    }
    exit;
  }
}
