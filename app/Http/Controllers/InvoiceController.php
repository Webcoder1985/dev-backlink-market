<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\PayproLog;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
class InvoiceController extends Controller
{
  public function index(Request $request)
  {

    $invoices = PayproLog::whereHas('order' , function($q) {
                      $q->where('pk_user', \Auth::user()->id)->where('order_status', '=', 20);
                    })->orderBy('id','desc')->get();


    return view('member.invoices', compact('invoices'));

  }
}
