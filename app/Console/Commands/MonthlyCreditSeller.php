<?php

namespace App\Console\Commands;

use App\Models\Notification;
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
use Storage;

class MonthlyCreditSeller extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:monthlycreditseller';

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
        $start_date = date('Y-m-d', strtotime('first day of last month'));
        $end_date = date('Y-m-d', strtotime('last day of last month'));
        // $start_date = '2021-12-01';
        //$end_date = date('Y-m-d');
        //DB::enableQueryLog();

        $order_details = OrderDetail::select('blmkt_order_details.id as order_detail_id', 'seller_id', 'page_price_seller', 'blmkt_order_details.refund_amount_seller')
            ->join('blmkt_orders', 'blmkt_orders.id', '=', 'blmkt_order_details.order_id')
            ->where('blmkt_orders.refund_status', '<', '2') // avoid fully refunded orders (2=fully refunded)
            ->where('blmkt_orders.order_status', '=', '20')
            ->where('blmkt_orders.created_at', '>=', $start_date)
            ->where('blmkt_orders.created_at', '<=', $end_date)
            ->WhereNull('seller_paid')
            ->get();
        //dd(DB::getQueryLog());
        foreach ($order_details as $OrdersDetail) {
            $amount_to_add = 0;
            if ($OrdersDetail->refund_amount_seller > 0) {
                $amount_to_add = $OrdersDetail->refund_amount_seller;
            } else {
                $amount_to_add = $OrdersDetail->page_price_seller;
            }

            $transaction = UserBalanceHistory::create([
                'user_id' => $OrdersDetail->seller_id,
                //1:Subscription Payment, 2:Withdraw Credits, 3:Refund, 4:Monthly Seller Credit
                'reference_type' => \Config::get('constants.reference_type.monthly_seller_credit'),
                'amount' => $amount_to_add,
                'order_id' => $OrdersDetail->order_detail_id,
                'history_description' => 'Monthly Credit Add - OrderDetailsID: ' . $OrdersDetail->order_detail_id,
                'balance_type' => \Config::get('constants.balance_type.credit'),
                'balance_account' => \Config::get('constants.balance_account.seller')

            ]);
            $user = User::where('id', $OrdersDetail->seller_id)->first();
            $user->balance_seller += $amount_to_add;
            $user->update();

            $order_detail = OrderDetail::where('id', $OrdersDetail->order_detail_id)->first();
            $order_detail->seller_paid = 1;
            $order_detail->update();

            $notification = new Notification();
            $notification->user_id = $OrdersDetail->seller_id;
            $notification->type ="Your seller balance has been credited with ".\Config::get('app.currency_symbol').$amount_to_add." for your ".Date('F', strtotime("last month"))." earnings.";
            $notification->save();

        }


        return 0;
    }
}
