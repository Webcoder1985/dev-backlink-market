<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PayproLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Links;
use Illuminate\Support\Facades\Log;
use DataTables;


class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            //$pages = Page::where('seller_id', '=', \Auth::user()->id)->where('is_active', '=', 1);
            $orders = OrderDetail::query()->select(['user_full_name', 'blmkt_orders.created_at as ca', 'blmkt_order_details.*'])->join('blmkt_orders', 'blmkt_orders.id', '=', 'blmkt_order_details.order_id');
            return DataTables::eloquent($orders)
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('columns'))) {
                        $searchVal = $request->get('columns');
                        $searchKeyword = $request->get('search');
                    }

                    if (isset($searchVal[0]) && !empty($searchVal[0]['search']['value'])) {
                        $data = $searchVal[0]['search']['value'];
                        if (!empty($data)) {
                            $query->where(function ($query) use ($data) {
                                $query->where('order_id', '=', $data);
                            });
                        }
                    }

                    if (isset($searchVal[1]) && !empty($searchVal[1]['search']['value'])) {
                        $data = $searchVal[1]['search']['value'];
                        if (!empty($data)) {
                            $query->where(function ($query) use ($data) {
                                $query->where('blmkt_orders.user_email', 'like', "%$data%");
                            });
                        }
                    }
                    if (isset($searchVal[2]) && !empty($searchVal[2]['search']['value'])) {
                        $data = $searchVal[2]['search']['value'];
                        if (!empty($data)) {
                            $query->where(function ($query) use ($data) {
                                $query->whereDate('blmkt_orders.created_at', '=', $data);
                            });
                        }
                    }
                    //$query->select(array('blmkt_orders.id','buyer_full_name','blmkt_orders.created_at','seller_full_name','page_url','page_pay_price','pause'));
                    //echo $query->toSql();exit;
                })
                ->editColumn('ca', function ($data) {
                    return date('Y-m-d', strtotime($data->ca));
                })
                ->editColumn('buyer_name', function ($data) {
                    $orderbuyer = Order::where('id', '=', $data->order_id)->get();

                    return $orderbuyer[0]->user_email;
                })
                ->editColumn('seller_full_name', function ($data) {

                    return $data->seller_email;
                })
                ->editColumn('page_url', function ($data) {
                    $pages = Page::where('id', '=', $data->page_id)->withTrashed()->get();

                    return $pages[0]->seller_site_url;
                })
                ->editColumn('page_pay_price', function ($data) {
                    return \Config::get('app.currency_symbol') . $data->page_price_seller;
                })
                ->editColumn('status', function ($data) {
                    if ($data->pause == 0) {
                        return 'Active';
                    } else {
                        return 'In Active';
                    }
                })
                ->editColumn('action', function ($data) {
                    return '<a  href="javascript:void(0)" id="' . $data->id . '" pu="' . $data->promoted_url . '" ant="' . $data->anchor_text . '" bet="' . $data->link_content . '" class="btn btn-plus btn-admin-expand-col"><i class="zmdi zmdi-plus"></i></a>';
                })
                ->rawColumns(['ca', 'buyer_name', 'page_url', 'page_pay_price', 'status', 'action'])
                ->make(true);
        }


        return view('admin.admin_orders');
    }

    public function orderLinks(Request $request)
    {
        /*$orders_active = Links::where('status', '>', '0')
            ->with(['page_by_id' => function ($q) {
            }])
            ->where('buyer_paid_until', '>=', now())
            ->get();
        $orders_inactive = Links::where('status', '<', '20')
            ->with(['page_by_id' => function ($q) {
            }])
            ->where('buyer_paid_until', '<', now())
            ->get();
            */
        $orders_active = Links::where('status', '=', '20')
            ->get();
        $orders_inactive = Links::where('status', '<', '20')
            ->get();
        return view('admin.order_links', compact('orders_active', 'orders_inactive'));

    }

    public function transaction(Request $request)
    {
        $orders = Order::with(['orderDetails' => function ($q) {
        }])
            ->with(['invoice' => function ($q) {
            }])
            ->get();

        return view('admin.transactions', compact('orders'));
    }

    public function adminnotifications(Request $request)
    {
        $all_notifications = Notification::orderBy('id', 'desc')->get();
        return view('admin.notifications', compact('all_notifications'));

    }

      public function income(Request $request)
    {
        if ($request->ajax()) {
            if(isset($request->get('columns')[0]['search']['value'])){
                $data=explode(',',$request->get('columns')[0]['search']['value']);
                 $orders = PayproLog::query()->select(["created_at", "pk_user", "order_item_balance_currency_vendor_amount", "order_item_balance_currency_paypro_expenses_amount"])
                     ->where('created_at', '>=', $data[0])
                     ->where('created_at', '<=', $data[1]);
                 $data1=$request->all();
                 $data1['columns'][0]['search']['value'] = null;
                 $request->merge($data1);
            } else {

                 $orders = PayproLog::query()->select(["created_at", "pk_user", "order_item_balance_currency_vendor_amount", "order_item_balance_currency_paypro_expenses_amount"])
                 ->where('created_at', '>=', date('Y-m-d', strtotime('first day of this month')))
                 ->where('created_at', '<=', date('Y-m-d', strtotime('last day of this month')));;
                       }



          //  $orders = PayproLog::query()->select(["created_at", "pk_user", "order_item_balance_currency_vendor_amount", "order_item_balance_currency_paypro_expenses_amount"]);
            $result= DataTables::eloquent($orders)
            /*    ->filter(function ($query) use ($request) {
                    if (!empty($request->get('columns'))) {
                        $searchVal = $request->get('columns');
                        $searchKeyword = $request->get('search');
                    }

                     if (isset($searchVal[0]) && !empty($searchVal[0]['search']['value'])) {
                        $data = explode(',', $searchVal[0]['search']['value']);

                        if (!empty($data)) {
                            $query->where(function ($query) use ($data) {
                                $query->where('created_at', '>=', '"'.$data[0].'"');
                                $query->where('created_at', '<=', '"'.$data[1].'"');
                            });
                        }
                    }
                  //  Log::info($query->toSql(), $query->getBindings());
                }) */
                ->editColumn('created_at', function ($data) {
                    return date('Y-m-d', strtotime($data->created_at));
                })
                ->editColumn('nachname', function ($data) {
                    $orderbuyer = User::where('id', '=', $data->pk_user)->pluck("lastname")->first();

                    return $orderbuyer;
                })
                ->editColumn('umsatz_brutto', function ($data) {

                    return $data->order_item_balance_currency_vendor_amount;
                })
                ->editColumn('umsatz_netto', function ($data) {

                    return $data->order_item_balance_currency_vendor_amount;
                })
                ->editColumn('ust', function ($data) {
                    return 0;
                })
                ->editColumn('steuersatz', function ($data) {
                    return 0;
                })
                ->editColumn('gebuehren', function ($data) {
                    return $data->order_item_balance_currency_paypro_expenses_amount;
                })
                ->editColumn('land', function ($data) {
                    return "Canada";
                })
                ->editColumn('kategorie', function ($data) {
                    return "Drittland";
                })
                ->editColumn('steuernummer', function ($data) {
                    return "0";
                })

                ->make(true);
            return $result;
        }


        return view('admin.admin_income');
    }
}
