<?php

namespace App\Http\Controllers;

use App\Mail\MailAdminNotification;
use App\Rules\ContainsInput;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Links;
use Illuminate\Support\Facades\Mail;

class BuyerOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['orderDetails' => function ($q) {

        }])->where('user_id', \Auth::user()->id)
            ->get();
        $inactiveorders = Order::with(['orderDetails' => function ($q) {

        }])->where('user_id', \Auth::user()->id)
            ->get();
        return view('member.buyer_orders', compact('orders', 'inactiveorders'));

    }

    public function buyerorders(Request $request)
    {
        $orders = Order::with(['orderDetails' => function ($q) {
        }])
            ->with(['invoice' => function ($q) {
            }])
            ->where('user_id', \Auth::user()->id)
            ->get();

        return view('member.buyer_orders', compact('orders'));
    }


    public function buyerlinks(Request $request)
    {
        $orders_active = Links::where('buyer_id', \Auth::user()->id)
            // ->where('buyer_paid_until', '>=', now())
            ->where('status', '=', 20)
            // ->with(['page_by_id' => function ($q) {
            // }])
            ->get();
        $orders_inactive = Links::where('buyer_id', \Auth::user()->id)
            //->where('buyer_paid_until', '<', now())
            ->where('status', '<', 20)
            // ->with(['page_by_id' => function ($q) {
            // }])
            ->get();

        return view('member.buyer_links', compact('orders_active', 'orders_inactive'));

    }

    public function stopSubscription($link_id)
    {


        if (\Auth::user()->user_status == 3) {
            //admin stop
            $link = Links::where('status', 20)->where('id', $link_id)->first();
            $reason = 3;
        } else {
            $link = Links::where('buyer_id', '=', \Auth::user()->id)->where('status', 20)->where('id', $link_id)->first();
            $reason = 1;
        }
        if (isset($link) && $link->id == $link_id) {
            $link->status = 0;
            if ($link->isDirty()) {
                //Remove link from Blog Post by sending all active links to the blog post with page_id
                $link->update(); // !important - needs to be updated because we submit all active orders to the blog now
                if (refundByLinkID($link_id, $link->seller_id, $reason) == "success") {
                    return response()->json(['success' => 'success'], 200);
                } else {
                    $link->status = 10;
                    $link->update();
                    return response()->json(['errors' => ['msg' => 'add_links_seller_blog: Blog update failed']], 422);
                }
            }
        } else {
            return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function resumeSubscription($link_id)
    {
        //$order_id = $request['order_id'];
        $link = Links::where('buyer_id', '=', \Auth::user()->id)->where('status', 10)->where('id', $link_id)->first();
        if (isset($link) && $link->id == $link_id) {
            $link->status = 20;
            $update = $link->update();
            if ($update) {
                //Add link to Blog Post by sending all active links to the blog post with page_id
                if (add_links_seller_blog($link->page_id) == "success") {
                    return response()->json(['success' => 'success'], 200);
                } else {
                    return response()->json(['errors' => ['msg' => 'add_links_seller_blog: Blog update failed']], 422);
                }
            }
        } else {
            return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function updateOrderDetail(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        if (isset($request['no_follow']) && $request['no_follow'] == "1")
            $data['no_follow'] = 1;
        else
            $data['no_follow'] = 0;
        $orders = Order::where('user_id', '=', \Auth::user()->id)->where('id', $request['order_id'])->get();
        if (count($orders) > 0) {
            $orderDetailsToUpdate = OrderDetail::where('id', $request['id'])->where('order_id', $request['order_id'])->update($data);
            if ($orderDetailsToUpdate)
                return response()->json(['success' => 'success'], 200);
            else
                return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);

        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function updateLinks(Request $request)
    {
        $request->validate([
            'promoted_url' => 'required|url',
            'anchor_text' => 'required',
            'link_content' => ['required', 'string', 'max:350', 'min:1', new ContainsInput($request, 'anchor_text')]
        ], [], [
            'promoted_url' => 'Your URL',
            'anchor_text' => 'Anchor Text',
            'link_content' => 'Content Text'
        ]);
        $data = $request->all();
        $link = Links::where('id', $data['id'])->where('buyer_id', \Auth::user()->id)->first();
        if ($link->updated_at->addDays(7) > date(now())) {
            return response()->json(['errors' => ['line1' => 'Only one change per 7 Days is allowed.', 'line2' => 'The next change becomes available on', 'line3' => date_format($link->updated_at->addDays(7), "Y-m-d H:i:s") . " GMT+1"]], 422);
        }
        if (isset($data['no_follow']) && $data['no_follow'] == "1")
            $data['no_follow'] = 1;
        else
            $data['no_follow'] = 0;

        $link->anchor_text = $data["anchor_text"];
        $link->link_content = $data["link_content"];
        $link->promoted_url = $data["promoted_url"];
        $link->no_follow = $data["no_follow"];
        if ($link->isDirty()) {
            $link->save();
            if (add_links_seller_blog($link->page_id) != "success") {
                $params['subject'] = "Error on add_link_seller_blog w. page_id: " . $link->page_id;
                $params['message'] = "Order_Controller.php L.221 Failed adding Links after Payment. Order Details id: " . $link->id . "\r\n" . "Check Page: " . $data->page_url . " for link: " . $data->promoted_url . " with anchor: " . $data->anchor_text;
                Mail::to(config('app.admin_email'))->send(new MailAdminNotification($params));
                return response()->json(['errors' => ['msg' => 'Remote Blog update failed!']], 422);
            } else {
                return response()->json(['success' => 'success'], 200);
            }
        } else {
            return response()->json(['errors' => ['msg' => 'We did not detect any changes. Nothing has done.']], 422);
        }


    }
}
