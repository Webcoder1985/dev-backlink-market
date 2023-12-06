<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
class NotificationController extends Controller
{
  public function index(Request $request)
  {

    $all_notifications = Notification::where('user_id', '=', \Auth::user()->id)->orderBy('id','desc')->get();

    return view('member.notifications', compact('all_notifications'));

  }


}
