<?php

namespace App\Http\Controllers;

use App\Models\Links;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use RexlManu\LaravelTickets\Models\Ticket;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      if(\Auth::user()->user_status != 3) {

          /*  $active_orders=Order::where('order_status',20)->where('user_id',\Auth::user()->id)->get();
            $active_orders_amount=$active_orders->sum("order_amount");
            $active_orders_count = $active_orders->count();
          'orderCount'=>$active_orders_count,'orderAmount'=>$active_orders_amount,
           */
          $active_links_buyer = Links::where('status', 20)->where('buyer_id', \Auth::user()->id)->get();
          $active_links_amount_buyer = $active_links_buyer->sum("page_price_buyer");
          $active_links_count_buyer = $active_links_buyer->count();

          $active_links_seller = Links::where('status', 20)->where('seller_id', \Auth::user()->id)->get();
          $active_links_amount_seller = $active_links_seller->sum("page_price_seller");
          $active_links_count_seller = $active_links_seller->count();

          $user_support_tickets = Ticket::where('user_id', \Auth::user()->id)->where('state', 'OPEN')->get()->count();

          $withdrawal_amount = 0;
          if (\Auth::user()->balance_seller >= 50) {
              $withdrawal_amount = \Auth::user()->balance_seller;
          }

          return view('home', ['linksCountBuyer' => $active_links_count_buyer, 'linksAmountBuyer' => $active_links_amount_buyer, 'linksCountSeller' => $active_links_count_seller, 'linksAmountSeller' => $active_links_amount_seller, 'openTickets' => $user_support_tickets, 'withdrawAmount' => $withdrawal_amount]);
      }
      else {
          $orderCount = Order::whereDate('created_at', \Carbon\Carbon::today())->get();
          $orderAmount = Order::whereDate('created_at', \Carbon\Carbon::today())->where('order_status', 20)->sum('order_amount');

          $userCount = User::whereDate('created_at', \Carbon\Carbon::today())->get();
          //exit;
          return view('home', ['orderCount' => $orderCount->count(), 'orderAmount' => $orderAmount, 'userCount' => $userCount->count()]);
      }
    }
}
