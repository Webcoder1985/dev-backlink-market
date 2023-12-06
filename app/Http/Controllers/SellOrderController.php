<?php

namespace App\Http\Controllers;

use App\Models\Links;
use App\Models\UserBalanceHistory;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Carbon;

class SellOrderController extends Controller
{
     public function sellerorders(Request $request)
    {

        /* $orders = OrderDetail::where('seller_id', '=', \Auth::user()->id)->get();
        $old_orders = $orders = OrderDetail::where("seller_id", "=", \Auth::user()->id)
            ->whereIn("order_id", function ($query) {
                $query->from("blmkt_orders")
                    ->select("id")
                    ->where("order_status", "=", 20)
                    ->where("created_at", "<", Carbon::now()->subDays(45));
            })
            ->get();
        */
        $orders = OrderDetail::where("seller_id", "=", \Auth::user()->id)
            ->whereIn("order_id", function ($query) {
                $query->from("blmkt_orders")
                    ->select("id")
                    ->where("order_status", "=", 20);
                // ->where("created_at", ">", Carbon::now()->subDays(45));
            })
            ->get();

        $monthly_earning = UserBalanceHistory::selectraw("sum(amount) as monthly_earnings,DATE_FORMAT(created_at, '%m-%Y') as month_year")
            ->where("user_id", "=", \Auth::user()->id)
            ->where("balance_type", "=",2) // show only credits, not debits (1)
            ->groupBy("month_year")
            ->orderBy("month_year", "desc")
            ->get();
        foreach ($monthly_earning as $item) {
            $test = $item;
        }

        return view('member.seller_orders', compact('orders'));

    }
    public function earnings(Request $request)
    {

        /* $orders = OrderDetail::where('seller_id', '=', \Auth::user()->id)->get();
        $old_orders = $orders = OrderDetail::where("seller_id", "=", \Auth::user()->id)
            ->whereIn("order_id", function ($query) {
                $query->from("blmkt_orders")
                    ->select("id")
                    ->where("order_status", "=", 20)
                    ->where("created_at", "<", Carbon::now()->subDays(45));
            })
            ->get();
        */
        $orders = OrderDetail::where("seller_id", "=", \Auth::user()->id)
            ->whereIn("order_id", function ($query) {
                $query->from("blmkt_orders")
                    ->select("id")
                    ->where("order_status", "=", 20);
                // ->where("created_at", ">", Carbon::now()->subDays(45));
            })
            ->get();

        $monthly_earning = UserBalanceHistory::selectraw("sum(amount) as monthly_earnings,DATE_FORMAT(created_at, '%m-%Y') as month_year")
            ->where("user_id", "=", \Auth::user()->id)
            ->where("balance_type", "=",2) // show only credits, not debits (1)
            ->groupBy("month_year")
            ->orderBy("month_year", "desc")
            ->get();
        foreach ($monthly_earning as $item) {
            $test = $item;
        }

        return view('member.seller_earnings', compact('orders', 'monthly_earning'));

    }

    public function stopSubscription($link_id, Request $request)
    {
        $link = Links::where('seller_id', '=', \Auth::user()->id)->where('status', 20)->where('id', $link_id)->first();
        if (isset($link) && $link->id == $link_id) {
            $link->status = 0;
            if ($link->isDirty()) {
                //Remove link from Blog Post by sending all active links to the blog post with page_id
                $link->update(); // !important - needs to be updated because we submit all active orders to the blog now
                if (refundByLinkID($link_id,$link->seller_id,2)=="success"){
                    return response()->json(['success' => 'success'], 200);
                } else {
                    $link->status = 20;
                    $link->update();
                      return response()->json(['errors' => ['msg' => 'add_links_seller_blog: Blog update failed']], 422);
                }
            }
        } else {
            return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }



    public function sellerlinks(Request $request)
    {
        $orders_active = Links::where('seller_id', \Auth::user()->id)
           // ->where('buyer_paid_until', '>=', now())
             ->where('status', '=', 20)
           // ->with(['page_by_id' => function ($q) {
           // }])
            ->get();


        return view('member.seller_links', compact('orders_active'));

    }
}
