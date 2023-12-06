<?php

namespace App\Console\Commands;

use App\Mail\MailAdminNotification;
use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\UserBalanceHistory;
use App\Models\User;
use App\Models\PayproLog;
use App\Models\Links;
use DB;
use Illuminate\Support\Facades\Mail;
use Storage;

class RefundProcess extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:refundprocess';

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

        //DB::enableQueryLog();
        $Links = Links::where('offline_counter', '>=', 7)->where('status', '!=', 0)->orderBy('id', 'desc')->get();
        $reason="Cancelled website offline";
        $refund_reason=4;
        foreach($Links as $Link)
        {
            $order_detail = OrderDetail::where('blmkt_links_id', $link_id)->where('order_id', $Link->last_order_id)->first();
            $order = Order::where('id', $order_detail->order_id)->first();
            $link_online_days = (new DateTime(now()))->diff(new DateTime($Link->created_at))->days;
            if ($link_online_days > 7) {
            // Partial Refund
                $total_month_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                $link_online_percentage= $link_online_days / $total_month_days; //bsp 20 tage online / 30 Monatstage= 0,67

                //Buyer
                $buyer_fee= $Link->page_price_buyer * env("TRANSACTION_FEE");
                $refund_amount_buyer= round($Link->page_price_buyer-($Link->page_price_buyer * $link_online_percentage)-$buyer_fee,2);

                //Seller
                $seller_fee= $Link->page_price_seller * env("TRANSACTION_FEE");
                $refund_amount_seller= round(($Link->page_price_seller * $link_online_percentage)-$seller_fee,2);
                $refund_status = 1; // 0:no refund, 1:partial refund, 2:total refund
        } else {
            // Full Refund
            // was online less than 7 days
            // Full refund to buyer minus 10% Transaction fee
            // No money to Seller
            $refund_amount_seller=0;
            $refund_amount_buyer = round($Link->page_price_buyer- ($Link->page_price_buyer * env("TRANSACTION_FEE")),2);
            $refund_status = 2; // 0:no refund, 1:partial refund, 2:total refund
        }


        // status needs to be 0 else add_links_seller_blog would still add the link to blog
        $link_detail = Links::where('id', $Link->id)->first();
        $link_detail->status = 0;
        $link_detail->update();
        if(add_links_seller_blog($Link->page_id) != "success") {
            $params['subject'] = "Error on add_link_seller_blog w. page_id: " . $Link->page_id;
            $params['message'] = "Refund process Failed removing Link after Refund. Order Details id: " . $order_detail->id . "\r\n" . "Check Page: " . $order_detail->page_url . " for link: " . $order_detail->promoted_url . " with anchor: " . $order_detail->anchor_text;
            Mail::to(config('app.admin_email'))->send(new MailAdminNotification($params));
        }

        $order_detail->refund_status = $refund_status;
        $order_detail->refund_amount_buyer = $refund_amount_buyer;
        $order_detail->refund_amount_seller = $refund_amount_seller;
        $order_detail->refund_reason = $refund_reason;
        $order_detail->update();

        $total_refund_amount=$order->refund_amount_buyer +  $order->refund_amount_seller;
        if ($refund_status == 2) {
            if(($total_refund_amount*0.2) + $total_refund_amount + $Link->page_price_buyer <=$order->order_amount){
                 $refund_status = 1; // 0:no refund, 1:partial refund, 2:total refund
            }
        }


        $order->refund_status = $refund_status;
        $order->refund_amount_buyer += $refund_amount_buyer;
        $order->refund_amount_seller += $refund_amount_seller;
        $order->update();

        // add refund amount to user balance
        $transaction = UserBalanceHistory::create([
            'user_id' => $Link->buyer_id,
            'reference_type' => \Config::get('constants.reference_type.refund'),
            'amount' => $refund_amount_buyer,
            'order_id' => $order_detail->id,
            'history_description' => 'Refund ('.$reason.') - OrderDetailsID: ' . $order_detail->id,
            'balance_type' => \Config::get('constants.balance_type.credit'),
            'balance_account' => \Config::get('constants.balance_account.buyer')
        ]);

            $notification = new Notification();
            $notification->user_id = $Link->buyer_id;
            $notification->type ="Link (ID".$Link->id.") has been refunded with ".\Config::get('app.currency_symbol').$refund_amount_buyer." because Link was offline for more than 7 days.";
            $notification->save();

        $user = User::where('id', $order->user_id)->first();
        $user->balance += $refund_amount_buyer;
        $user->update();

        }



        return 0;
    }
}
