<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\PageCategory;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use DataTables;
use DB;
use Illuminate\Support\Facades\Config;

class AdminSellerBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        
        if($request->ajax())
        {
            //$pages = Page::where('seller_id', '=', \Auth::user()->id)->where('is_active', '=', 1);
            $sites = SellerSite::query();
            return DataTables::eloquent($sites)
                ->filter(function($query) use ($request)
                {
                  if (!empty($request->get('fUserID'))) {
                      $query->Where('seller_id',$request->get('fUserID'));
                  }
                  if (!empty($request->get('fDomain'))) {
                      $query->Where('site_url','like','%'.$request->get('fDomain').'%');
                  }
                  if ($request->get('fStatus')!="all") {
                      $query->Where('is_active',$request->get('fStatus'));
                  }
                  if ($request->get('fBanStatus')!="all") {
                      $query->Where('is_ban',$request->get('fBanStatus'));
                  }
                  //echo $query->toSql();exit;
                })
                ->editColumn('seller_id', function($data) {
                    return  $data->seller_id;
                })
                ->editColumn('site_url', function($data) {
                    return  $data->site_url;
                })
                ->editColumn('created_at', function($data) {
                    return  date('Y-m-d',strtotime($data->created_at));
                })
                ->editColumn('is_active', function($data) {
                  if($data->is_active == 1)
                  {
                    return 'Active';
                  }
                  else {
                    return 'In Active';
                  }
                })
                ->editColumn('action', function($data) {
                  return '<a  href=' . URL('admin-blog-pages/' . $data->id) . ' id="' . $data->id . '" site_url="' . $data->site_url . '"  class="btn btn-primary btn-sm">Pages</a> <button  onclick="deleteBlog(' . $data->id . ')" class="btn btn-danger btn-sm">Delete</button> <button  onclick="banBlog(' . $data->id . ',' . $data->is_ban . ')" class="btn btn-warning btn-sm">' . ($data->is_ban === 0 ? 'Ban' : 'Relist') . '</button>';
              })


                ->rawColumns(['seller_id','site_url','is_active','action'])
                ->make(true);
        }



        return view('admin.sites');
    }


    public function getBlogPages($id,Request $request){

      if($request->ajax())
      { 
          $pages = Page::where('seller_site_id', $id);
          return DataTables::eloquent($pages)
              ->filter(function($query) use ($request)
              {
                  if(!empty($request->get('columns')))
                  {
                      $searchVal = $request->get('columns');
                      $searchKeyword = $request->get('search');
                      if(isset($searchKeyword) && !empty($searchKeyword) && !empty($searchKeyword['value']))
                      {
                          $query->where(function ($query) use ($searchKeyword) {
                              $query->orWhere('seller_site_url', 'like', "%{$searchKeyword['value']}%")
                                  ->orWhere('seller_site_page_url', 'like', "%{$searchKeyword['value']}%")
                                  ->orWhere('title', 'like', "%{$searchKeyword['value']}%");
                          });
                      }

                      if(isset($searchVal[1]) && !empty($searchVal[1]['search']['value']))
                      {
                          $data = explode(',', $searchVal[1]['search']['value']);
                          if(!empty($data))
                          {
                              $query->where(function ($query) use ($data) {
                                  $query->where('moz_da', '>=', $data[0]);
                                  $query->where('moz_da', '<=', $data[1]);
                              });
                          }
                      }

                      if(isset($searchVal[2]) && !empty($searchVal[2]['search']['value']))
                      {
                          $data = explode(',', $searchVal[2]['search']['value']);
                          if(!empty($data))
                          {
                              $query->where(function ($query) use ($data) {
                                  $query->where('moz_pa', '>=', $data[0]);
                                  $query->where('moz_pa', '<=', $data[1]);
                              });
                          }
                      }

                      if(isset($searchVal[3]) && !empty($searchVal[3]['search']['value']))
                      {
                          $data = explode(',', $searchVal[3]['search']['value']);
                          if(!empty($data))
                          {
                              $query->where(function ($query) use ($data) {
                                  $query->where('maj_tf', '>=', $data[0]);
                                  $query->where('maj_tf', '<=', $data[1]);
                              });
                          }
                      }

                      if(isset($searchVal[4]) && !empty($searchVal[4]['search']['value']))
                      {
                          $data = explode(',', $searchVal[4]['search']['value']);
                          if(!empty($data))
                          {
                              $query->where(function ($query) use ($data) {
                                  $query->where('maj_cf', '>=', $data[0]);
                                  $query->where('maj_cf', '<=', $data[1]);
                              });
                          }
                      }
                      if (isset($searchVal[5]) && !empty($searchVal[5]['search']['value'])) {
                          $data = explode(',', $searchVal[5]['search']['value']);
                          if (!empty($data)) {
                              $query->where(function ($query) use ($data) {
                                  $query->where('maj_bl', '>=', $data[0]);
                                  $query->where('maj_bl', '<=', $data[1]);
                              });
                          }
                      }
                      if (isset($searchVal[6]) && !empty($searchVal[6]['search']['value'])) {
                          $data = explode(',', $searchVal[6]['search']['value']);
                          if (!empty($data)) {
                              $query->where(function ($query) use ($data) {
                                  $query->where('rd', '>=', $data[0]);
                                  $query->where('rd', '<=', $data[1]);
                              });
                          }
                      }

                      if (isset($searchVal[7]) && !empty($searchVal[7]['search']['value'])) {
                          $data = explode(',', $searchVal[7]['search']['value']);
                          if (!empty($data)) {
                              $query->where(function ($query) use ($data) {
                                  $query->where('obl', '>=', $data[0]);
                                  $query->where('obl', '<=', $data[1]);
                              });
                          }
                      }

                      if (isset($searchVal[13]) && !empty($searchVal[13]['search']['value'])) {
                          $data = explode(',', $searchVal[13]['search']['value']);
                          if (!empty($data)) {
                              $query->where(function ($query) use ($data) {
                                  $query->where('page_price_buyer', '>=', $data[0]);
                                  $query->where('page_price_buyer', '<=', $data[1]);
                              });
                          }
                      }

                      if (isset($searchVal[9]) && !empty($searchVal[9]['search']['value'])) {
                          $data = explode(',', $searchVal[9]['search']['value']);
                          if (!empty($data) && !in_array('all', $data)) {
                              $query->whereIn('language', $data);
                          }
                      }

                      if (isset($searchVal[10]) && !empty($searchVal[10]['search']['value'])) {
                          $data = explode(',', $searchVal[10]['search']['value']);
                          if (!empty($data) && !in_array('all', $data)) {
                              $query->whereIn('tld', $data);
                          }
                      }

                      if (isset($searchVal[11]) && !empty($searchVal[11]['search']['value'])) {
                          $data = explode(',', $searchVal[11]['search']['value']);
                          if (!empty($data) && !in_array('all', $data)) {
                              $query->whereIn('category', $data);
                          }
                      }

                      if (isset($searchVal[8]) && !empty($searchVal[8]['search']['value'])) {
                          $data = explode(',', $searchVal[8]['search']['value']);
                          if (!empty($data) && !in_array('all', $data)) {
                              $query->whereIn('country', $data);
                          }
                      }
                      if (isset($searchVal[12]) && !empty($searchVal[12]['search']['value'])) {
                          $data = $searchVal[12]['search']['value'];
                          if ($data != "all" && $data != "") {
                              $query->where('is_active', ($data == "active" ? "1" : "0"));
                          }
                      }
                  }

              })
              ->editColumn('moz_da', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->moz_da;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                      return $html;
              })
              ->editColumn('moz_pa', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->moz_pa;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                      return $html;
              })
              ->editColumn('maj_tf', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else {
                          if ($data->indexed >= 0)
                              $html = $data->maj_tf;
                          else
                              $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      }
                  return $html;
              })
              ->editColumn('maj_cf', function ($data) {
                  $html = '';
                  if (is_null($data->indexed))
                      $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                  else {
                      if ($data->indexed >= 0)
                          $html = $data->maj_cf;
                      else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                  }
                  return $html;
              })
              ->editColumn('maj_bl', function ($data) {
                  $html = '';
                  if (is_null($data->indexed))
                      $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                  else {
                      if ($data->indexed >= 0)
                          $html = $data->maj_bl;
                      else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                  }
                  return $html;
              })
              ->editColumn('rd', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->rd;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                      return $html;
              })
              ->editColumn('obl', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->obl;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                      return $html;
              })
              ->editColumn('country', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->country;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                      return $html;
              })
              ->editColumn('language', function($data) {
                      $html = '';
                      if(is_null($data->indexed))
                        $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                      else
                      {
                        if($data->indexed >= 0)
                          $html = $data->language;
                        else
                          $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                       }
                  return $html;
              })
              ->editColumn('category', function ($data) {
                  $html = '';
                  if (is_null($data->indexed))
                      $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                  else
                      $html = $data->title;
                  return $html;
              })
              ->editColumn('select_pages', function ($data) {
                  return '<input type="checkbox" name="select_pages[]" value="' . $data->id . '" />';
              })
              ->editColumn('page_price_buyer', function ($data) {
                  return Config::get('app.currency_symbol') . $data->page_price_buyer;
              })
              ->editColumn('is_active', function($data) {
                  $html = '';
                  if ($data->is_active) {
                      $html = '<span class="badge badge-success">Yes</span>';
                  } else {
                      $html = '<span class="badge badge-danger">No</span>';
                  }
                  if ($data->is_ban) {
                      $html .= ' <span class="badge badge-danger">Ban</span>';
                  }
                  return $html;
              })
              ->editColumn('actions', function ($data) {
                  return '
              <button  onclick="editPage(' . $data->id . ');return false;" class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
              <button onclick="deletePage(' . $data->id . ');return false;" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>';
              })
              ->rawColumns(['moz_da', 'moz_pa', 'maj_tf', 'maj_cf', 'maj_bl', 'rd', 'obl', 'country', 'language', 'category', 'select_pages', 'page_price_buyer', 'is_active', 'actions'])
              ->make(true);
      }
        {

            $da_min = Page::where('seller_site_id', '=', $id)->min('moz_da');
            $da_min = ((isset($da_min) && $da_min >= 0) ? $da_min : "0");
            $da_max = Page::where('seller_site_id', '=', $id)->max('moz_da');
            $da_max = ((isset($da_max) && $da_max >= 0) ? $da_max : "0");
            $pa_min = Page::where('seller_site_id', '=', $id)->min('moz_pa');
            $pa_min = ((isset($pa_min) && $pa_min >= 0) ? $pa_min : "0");
            $pa_max = Page::where('seller_site_id', '=', $id)->max('moz_pa');
            $pa_max = ((isset($pa_max) && $pa_max >= 0) ? $pa_max : "0");
            $tf_min = Page::where('seller_site_id', '=', $id)->min('maj_tf');
            $tf_min = ((isset($tf_min) && $tf_min >= 0) ? $tf_min : "0");
            $tf_max = Page::where('seller_site_id', '=', $id)->max('maj_tf');
            $tf_max = ((isset($tf_max) && $tf_max >= 0) ? $tf_max : "0");
            $cf_min = Page::where('seller_site_id', '=', $id)->min('maj_cf');
            $cf_min = ((isset($cf_min) && $cf_min >= 0) ? $cf_min : "0");
            $cf_max = Page::where('seller_site_id', '=', $id)->max('maj_cf');
            $cf_max = ((isset($cf_max) && $cf_max >= 0) ? $cf_max : "0");
            $bl_min = Page::where('seller_site_id', '=', $id)->min('maj_bl');
            $bl_min = ((isset($bl_min) && $bl_min >= 0) ? $bl_min : "0");
            $bl_max = Page::where('seller_site_id', '=', $id)->max('maj_bl');
            $bl_max = ((isset($bl_max) && $bl_max >= 0) ? $bl_max : "0");
            $obl_min = Page::where('seller_site_id', '=', $id)->min('obl');
            $obl_min = ((isset($obl_min) && $obl_min >= 0) ? $obl_min : "0");
            $obl_max = Page::where('seller_site_id', '=', $id)->max('obl');
            $obl_max = ((isset($obl_max) && $obl_max >= 0) ? $obl_max : "0");
            $price_min = Page::where('seller_site_id', '=', $id)->min('page_price_buyer');
            $price_min = ((isset($price_min) && $price_min >= 0) ? $price_min : "0");
            $price_max = Page::where('seller_site_id', '=', $id)->max('page_price_buyer');
            $price_max = ((isset($price_max) && $price_max >= 0) ? $price_max : "0");
            $rd_min = Page::where('seller_site_id', '=', $id)->min('rd');
            $rd_min = ((isset($rd_min) && $rd_min >= 0) ? $rd_min : "0");
            $rd_max = Page::where('seller_site_id', '=', $id)->max('rd');
            $rd_max = ((isset($rd_max) && $rd_max >= 0) ? $rd_max : "0");
            $languages = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('language')->select('language')->get();
            $categories = Category::where('parent_level', 0)->get();
            log::info($categories);
            $countries = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('country')->select('country')->get();
            $tlds = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('tld')->select('tld')->get();
        }
      $showSuccess=false;
        return view('member.site_pages', compact('da_min', 'da_max', 'pa_min', 'pa_max', 'tf_min', 'tf_max', 'cf_min', 'cf_max', 'bl_min', 'bl_max', 'languages', 'categories', 'tlds', 'price_min', 'price_max', 'obl_min', 'obl_max', 'rd_min', 'rd_max', 'countries','showSuccess'), ['id' => $id]);
    }


    public function getBlogPagesAction($id, Request $request)
    {
        $data = $request->all();

        if(isset($data['delete_pages']) || isset($data['ban_pages'])){
          if(isset($data['select_pages']))
          {
            foreach($data['select_pages'] as $select_page)
            {
              $page = Page::where('id', $select_page)->first();
              if($page)
              {
                if(isset($data['delete_pages']) && $data['delete_pages']=='Delete')
                  $page->delete();
                else if(isset($data['ban_pages']) && $data['ban_pages']=='Ban'){
                  $page['is_ban'] = 1;
                    $page->update();
                    $order_details_update = OrderDetail::where('page_id', $page->id)->update(['pause' => 1]);
                }
              }
            }
          }
        }
        return redirect()->route('admin-blog-pages', [$id])->with('message', 'Pages deleted!!!');
    }

    public function banBlogAction($id){
      $site=SellerSite::where('id',$id)->first();
      if($site){
          $site['is_ban'] = 1;
          $site->update();
      }
    }

    public function relistBlogAction($id){
      $site=SellerSite::where('id',$id)->first();
      if($site){
          $site['is_ban'] = 0;
          $site->update();
      }
    }

    public function deleteBlogAction($id){
      $site=SellerSite::where('id',$id)->first();
      if($site){
        $site->delete();
      }
    }

}
