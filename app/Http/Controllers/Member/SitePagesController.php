<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use DateTime;
use http\Message;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\PageCategory;
use App\Models\Category;
use DataTables;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Jobs\FindPageCategories;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\Log;


class SitePagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sitePages($id, Request $request)
    {

        if ($request->ajax()) {
            $pages = Page::where('seller_site_id', '=', $id)->where('seller_id', '=', \Auth::user()->id)->where('is_ban', '=', 0);

            return DataTables::eloquent($pages)
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('columns'))) {
                        $searchVal = $request->get('columns');
                        $searchKeyword = $request->get('search');
                        if (isset($searchKeyword) && !empty($searchKeyword) && !empty($searchKeyword['value'])) {
                            $query->where(function ($query) use ($searchKeyword) {
                                $query->orWhere('seller_site_url', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('seller_site_page_url', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('title', 'like', "%{$searchKeyword['value']}%");
                            });
                        }

                        if (isset($searchVal[2]) && !empty($searchVal[2]['search']['value'])) {
                            $data = explode(',', $searchVal[2]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('moz_da', '>=', $data[0]);
                                    $query->where('moz_da', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[3]) && !empty($searchVal[3]['search']['value'])) {
                            $data = explode(',', $searchVal[3]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('moz_pa', '>=', $data[0]);
                                    $query->where('moz_pa', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[4]) && !empty($searchVal[4]['search']['value'])) {
                            $data = explode(',', $searchVal[4]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('maj_tf', '>=', $data[0]);
                                    $query->where('maj_tf', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[5]) && !empty($searchVal[5]['search']['value'])) {
                            $data = explode(',', $searchVal[5]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('maj_cf', '>=', $data[0]);
                                    $query->where('maj_cf', '<=', $data[1]);
                                });
                            }
                        }
                        if (isset($searchVal[6]) && !empty($searchVal[6]['search']['value'])) {
                            $data = explode(',', $searchVal[6]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('maj_bl', '>=', $data[0]);
                                    $query->where('maj_bl', '<=', $data[1]);
                                });
                            }
                        }
                        if (isset($searchVal[7]) && !empty($searchVal[7]['search']['value'])) {
                            $data = explode(',', $searchVal[7]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('rd', '>=', $data[0]);
                                    $query->where('rd', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[8]) && !empty($searchVal[8]['search']['value'])) {
                            $data = explode(',', $searchVal[8]['search']['value']);
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

                        /*if (isset($searchVal[10]) && !empty($searchVal[10]['search']['value'])) {
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
                        }*/
                        if (isset($searchVal[12]) && !empty($searchVal[12]['search']['value'])) {
                            $data = $searchVal[12]['search']['value'];
                            if ($data != "all" && $data != "") {
                                $query->where('is_active', ($data == "active" ? "1" : "0"));
                            }
                        }
                    }

                })
                ->editColumn('moz_da', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->moz_da)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->moz_da;
                            }
                    }
                    return $html;
                })
                ->editColumn('moz_pa', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->moz_pa)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->moz_pa;
                            }
                    }
                    return $html;
                })
                ->editColumn('maj_tf', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->maj_tf)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->maj_tf;
                            }
                    }
                    return $html;
                })
                ->editColumn('maj_cf', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->maj_cf)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->maj_cf;
                            }
                    }
                    return $html;
                })
                ->editColumn('maj_bl', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->maj_bl)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->maj_bl;
                            }
                    }
                    return $html;
                })
                ->editColumn('rd', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->rd)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->rd;
                            }
                    }
                    return $html;
                })
                ->editColumn('obl', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->obl)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->obl;
                            }
                    }
                    return $html;
                })
                ->editColumn('country', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->country)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->country;
                            }
                    }
                    return $html;
                })
                ->editColumn('language', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } else {
                        if ($data->indexed == 1)
                            if (is_null($data->language)) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            } else {
                                $html = $data->language;
                            }
                    }
                    return $html;
                })
                ->editColumn('category', function ($data) {

                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } elseif ($data->indexed == 1) {
                        $page_categories_count = PageCategory::where('page_id', '=', $data->id)->count();
                        if ($page_categories_count > 0) {
                            $page_categories = PageCategory::where('page_id', '=', $data->id)->get();
                            $page_categories_title = "";
                            $html = $page_categories_count;
                            try {
                                foreach ($page_categories as $page_category) {
                                    if (isset($page_category->category_title))
                                        $page_categories_title .= $page_category->category_title->title . ",";
                                    else
                                        $page_categories_title = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                                }
                                $page_categories_title = rtrim($page_categories_title, ",");
                                $html = $page_categories_title;
                            } catch (Exception $e) {
                                $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                            }
                        } else {
                            $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                        }
                    } elseif ($data->indexed == 0) {
                        $html = "";
                    }

                    return $html;
                })
                ->editColumn('select_pages', function ($data) {
                    if ($data->indexed === 0)
                        return '<input type="checkbox" name="select_pages[]" disabled value="' . $data->id . '" />';
                    else
                        return '<input type="checkbox" name="select_pages[]" value="' . $data->id . '" />';
                })
                ->editColumn('page_price_seller', function ($data) {
                    if ($data->page_price_seller > 0) {
                        return Config::get('app.currency_symbol') . $data->page_price_seller;
                    } else {
                        return '-';
                    }

                })
                ->editColumn('indexed', function ($data) {
                    $html = '';
                    if (is_null($data->indexed)) {
                        if ($data->is_active == 1) $html = '<i title="Updating" class="zmdi zmdi-time-restore"></i>';
                    } else if ($data->indexed == 1) {
                        $html = '<i title="Indexed" class="zmdi zmdi-check"></i>';
                    } else {
                        $html = '<i title="Not Indexed" class="zmdi zmdi-close error"></i>';
                    }
                    return $html;
                })
                ->editColumn('is_active', function ($data) {
                    $html = '';
                    if ($data->is_active == 1) {
                        if ($data->indexed === 0)
                            $html = '<a class="show-tooltip"  data-toggle="tooltip" data-placement="top" data-original-title="Reason: Page is not indexed yet"><span class="badge badge-danger">disabled</span></a>';
                        else
                            $html = '<span class="badge badge-success">Yes</span>';
                    } else {
                        $html = '<span class="badge badge-danger">No</span>';
                    }
                    return $html;
                })
                ->editColumn('actions', function ($data) {
                    $html = "";
                    if ($data->is_active == 1 && $data->page_price_buyer > 0) {
                        $html = '<button  onclick="editPage(' . $data->id . ');return false;" class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>';
                    }
                    if ($data->indexed === 1) {
                        $html .= '<button onclick="deletePage(' . $data->id . ');return false;" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>';
                    }
                    return $html;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at;
                })
                ->rawColumns(['moz_da', 'moz_pa', 'maj_tf', 'maj_cf', 'maj_bl', 'rd', 'obl', 'country', 'language', 'category', 'select_pages', 'page_price_buyer', 'indexed', 'is_active', 'actions', 'page_price_seller'])
                ->make(true);
        }
        {
            $showSuccess = false;
            if (str_contains($request->headers->get('referer'), "?firstSync=true") == true) {
                $showSuccess = true;
            };
            $page = Page::where('seller_site_id', '=', $id);
            $da_min = $page->min('moz_da');
            $da_min = ((isset($da_min) && $da_min >= 0) ? $da_min : "0");
            $da_max = $page->max('moz_da');
            $da_max = ((isset($da_max) && $da_max >= 0) ? $da_max : "0");
            $pa_min = $page->min('moz_pa');
            $pa_min = ((isset($pa_min) && $pa_min >= 0) ? $pa_min : "0");
            $pa_max = $page->max('moz_pa');
            $pa_max = ((isset($pa_max) && $pa_max >= 0) ? $pa_max : "0");
            $tf_min = $page->min('maj_tf');
            $tf_min = ((isset($tf_min) && $tf_min >= 0) ? $tf_min : "0");
            $tf_max = $page->max('maj_tf');
            $tf_max = ((isset($tf_max) && $tf_max >= 0) ? $tf_max : "0");
            $cf_min = $page->min('maj_cf');
            $cf_min = ((isset($cf_min) && $cf_min >= 0) ? $cf_min : "0");
            $cf_max = $page->max('maj_cf');
            $cf_max = ((isset($cf_max) && $cf_max >= 0) ? $cf_max : "0");
            $bl_min = $page->min('maj_bl');
            $bl_min = ((isset($bl_min) && $bl_min >= 0) ? $bl_min : "0");
            $bl_max = $page->max('maj_bl');
            $bl_max = ((isset($bl_max) && $bl_max >= 0) ? $bl_max : "0");
            $obl_min = $page->min('obl');
            $obl_min = ((isset($obl_min) && $obl_min >= 0) ? $obl_min : "0");
            $obl_max = $page->max('obl');
            $obl_max = ((isset($obl_max) && $obl_max >= 0) ? $obl_max : "0");
            $price_min = $page->min('page_price_buyer');
            $price_min = ((isset($price_min) && $price_min >= 0) ? $price_min : "0");
            $price_max = $page->max('page_price_buyer');
            $price_max = ((isset($price_max) && $price_max >= 0) ? $price_max : "0");
            $rd_min = $page->min('rd');
            $rd_min = ((isset($rd_min) && $rd_min >= 0) ? $rd_min : "0");
            $rd_max = $page->max('rd');
            $rd_max = ((isset($rd_max) && $rd_max >= 0) ? $rd_max : "0");
            $languages = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('language')->select('language')->get();
            $categories = Category::where('parent_level', '=', 0)->get();
            //$categories = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('category')->select('category')->get();
            $countries = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('country')->select('country')->get();
            $tlds = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('tld')->select('tld')->get();
        }
        return view('member.site_pages', compact('da_min', 'da_max', 'pa_min', 'pa_max', 'tf_min', 'tf_max', 'cf_min', 'cf_max', 'bl_min', 'bl_max', 'languages', 'categories', 'tlds', 'price_min', 'price_max', 'obl_min', 'obl_max', 'rd_min', 'rd_max', 'countries', 'showSuccess'), ['id' => $id]);
    }

    public function sellerCreatePageForm($id = null, Request $request)
    {
        $page = [];
        $site = "";
        if (isset($id) && !empty($id)) {
            
            $site = SellerSite::where('seller_id', \Auth::user()->id)->where('id', $id)->select('id', 'site_url')->first();
        }

        return view('member.seller_create_page_form', compact('site', 'page'));
    }

    public function activatePagesRecheck($id, Request $request)
    {

        $data = $request->all();

        if (!isset($data['selected_pages'])) {
            return redirect()->route('site_pages', [$id])->with('message', "No Pages selected");
        }
        if (count($data['selected_pages']) > 0) {
            foreach ($data['selected_pages'] as $select_page) {
                $page = Page::where('seller_id', \Auth::user()->id)->where('seller_site_page_id', $select_page)->where('deleted_at', NULL)->orderBY("created_at", "desc")->first();
                if ($page) {
                    $page->is_active = 1;
                    $page->update();
                }
                $new_pages[] = $page;
            }

            if (count($new_pages) > 0) {
                addPagesForMetricCheck($new_pages);
            }
        }
        return redirect()->route('site_pages', [$id])->with('message', "Page(s) added!");

    }

    public function deactivatePagesRecheck($id, Request $request)
    {
        $data = $request->all();
        if (isset($data['selected_pages'])) {
            $all_pages = explode(",", $data["all_pages"]);
            // remove checked from all_pages
            $pages_to_deactivate = array_diff($all_pages, $data['selected_pages']);
        } else {
            $pages_to_deactivate = explode(",", $data["all_pages"]);
        }
        if (count($pages_to_deactivate) > 0) {
            foreach ($pages_to_deactivate as $select_page) {
                $page = Page::where('seller_id', \Auth::user()->id)->where('seller_site_page_id', $select_page)->where('deleted_at', NULL)->orderBY("created_at", "desc")->first();
                if ($page) {
                    $page->is_active = 0;
                    $page->update();
                }
            }
        }
        return redirect()->route('site_pages', [$id])->with('message', "Page(s) deactivated!");

    }

    public function sitePagesAction($id, Request $request)
    {
        $data = $request->all();
        $message = "Oops, Something went wrong.";
        if (isset($data['activate_pages']) || isset($data['deactivate_pages']) || isset($data['delete_pages'])) {
            if (isset($data['select_pages'])) {
                foreach ($data['select_pages'] as $select_page) {
                    $page = Page::where('seller_id', \Auth::user()->id)->where('id', $select_page)->first();
                    if ($page) {
                        if (isset($data['delete_pages']) && $data['delete_pages'] == 'Delete') {
                            $message = "Page(s) deleted!";
                            $page->delete();
                        } elseif (isset($data['activate_pages']) && $data['activate_pages'] == 'Activate') {
                            // if ($page->indexed !== 0) { // forbid to activate non indexed pages but allow to activate never activated before (NULL) and indexed (1)
                                
                            // }
                            if ($page->last_metric_update_time === NULL) {
                                $new_pages[] = $page;
                                addPagesForMetricCheck($new_pages);
                            }
                            $message = "Page(s) activated!";
                            $page->is_active = 1;
                            $page->update();
                        } elseif (isset($data['deactivate_pages']) && $data['deactivate_pages'] == 'Deactivate') {
                            $message = "Page(s) deactivated!";
                            $page->is_active = 0;
                            $page->update();
                        }
                    }
                }
            }
        }

        return redirect()->route('site_pages', [$id])->with('message', $message);


    }

    public function editPage(Request $request)
    {
        $data = $request->all();
        $page = Page::where('seller_id', \Auth::user()->id)->where('id', $data["id"])->first();
        if ($page) {
            if ($page->page_price_seller != $data["page_price_seller"]) {
                // avoid changing price 7 days before month ends
                $x = new DateTime();
                $y = clone $x;
                $y->modify('last day of this month');
                $remainingMonthDays= $y->format('d') - $x->format('d');
                if($remainingMonthDays>7){
                $seller_buyer = getBuyerPriceFromSellerPrice($data["page_price_seller"], $page->rd);
                $page->page_price_seller = $data["page_price_seller"];
                $page->page_price_buyer = $seller_buyer;
                } else {
                    return response()->json(['errors' => ['msg' => 'You cannot change the Price within the last 7 days of a month.']], 422);
                }

            }
            if (count($data['categories']) > 0) {
                PageCategory::where('page_id', '=', $page->id)->delete();
                foreach ($data['categories'] as $new_cat) {
                    PageCategory::updateOrCreate(
                        [
                            'page_id' => $page->id,
                            'category' => $new_cat
                        ],
                    );
                }
            }

            if (isset($data["is_active"])) {
                $page->is_active = 1;
            } else $page->is_active = 0;
            $page->update();

            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }


    public function savePage(Request $request)
    {
        $data = $request->all();
        $site = SellerSite::find($data['seller_site_id']);
        if (!isset($site)) {
            return response()->json(['errors' => ['msg' => 'Not your Site']], 422);
        }
        if (isset($data['seller_site_page_urls'])) {
            $urls = explode("\n", trim($data['seller_site_page_urls']));
        } elseif (isset($data['seller_site_page_url'])) {
            $urls = array($data['seller_site_page_url']);
        }


        //$urls = array_filter($urls, 'trim');

        if (count($urls) > 0) {
            $urls = array_map('trim', $urls);
            $all_pages = array();
            $url = $site->site_url . "/wp-json/wp/v2/get-urls";
            $client = new \GuzzleHttp\Client(['verify' => false]);
            try {
                $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
                $all_pages = json_decode($response->getBody(), true);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return response()->json(['errors' => ['msg' => $e->getMessage()]], $e->getCode());
            }

            if (count($all_pages) > 0) {
                $invalid_urls = array();
                $ban_urls = array();
                $found_urls = array();
                $duplicate_urls = array();
                $deleted_urls = array();
                foreach ($urls as $url) {

                    if (in_array($url, $all_pages)) {
                        //echo $url.": Found<br />\n";
                        $page_exist = Page::where('seller_id', \Auth::user()->id)->where('seller_site_page_url', $url)->where('seller_site_id', $data['seller_site_id'])->whereNull('deleted_at')->first();
                        //print_r($page_exist);exit;
                        if (!$page_exist) {
                            $found_urls[] = $url;
                        } else if ($page_exist->is_ban == 1) {
                            $ban_urls[] = $url;
                        } else if (isset($page_exist->deleted_at)) {
                            $deleted_urls[] = $url;
                        } else if ($page_exist->seller_site_page_url == $url) {
                            $duplicate_urls[] = $url;
                        }
                    } else {
                        $invalid_urls[] = $url;
                    }
                }
                if (count($found_urls) > 0) {
                    $all_pages = array();
                    $url = $site->site_url . "/wp-json/wp/v2/get-posts-pages";
                    $client = new \GuzzleHttp\Client(['verify' => false]);
                    try {
                        $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
                        $all_pages = json_decode($response->getBody(), true);
                    } catch (\GuzzleHttp\Exception\ClientException $e) {
                        return response()->json(['errors' => ['msg' => $e->getMessage()]], $e->getCode());
                    }
                    $new_pages = array();
                    foreach ($found_urls as $url) {
                        $found_url = false;
                        foreach ($all_pages as $key => $val) {
                            //echo $val['url']."-".$url."<br />\n";
                            if (strcmp($val['url'], trim($url)) == 0) {
                                //echo $url.": Found<br />\n";
                                $page_exist = Page::where('seller_id', \Auth::user()->id)->where('seller_site_page_url', $val['url'])->where('seller_site_id', $data['seller_site_id'])->whereNull('deleted_at')->first();
                                //print_r($page_exist);exit;
                                if (!$page_exist) {
                                    $tags = "0";
                                    if ($val["tags"] != false) {
                                        foreach ($val["tags"] as $value) {
                                            $tags_array[] = $value["name"];
                                        }
                                        if (count($tags_array) > 0) {
                                            $tags = implode(",", $tags_array);
                                        }
                                    }
                                    $content = strip_tags($val['content']);
                                    $plain = str_replace(array("'", '"'), '', $content);
                                    $page = Page::updateOrCreate([
                                        'seller_id' => \Auth::user()->id,
                                        'seller_site_id' => $site->id,
                                        'seller_site_url' => $site->site_url,
                                        'seller_site_page_id' => $val['ID'],
                                        'seller_site_page_url' => $val['url'],
                                        'title' => $val['title'],
                                        'tags' => $tags,
                                        'is_active' => 1,
                                        'type' => $val['type'],
                                        'publish_date' => $val['publish_date'],
                                        'content' => $plain,
                                        'tld' => $site->tld,
                                        'country' => $site->country

                                    ]);
                                    $new_pages[] = $page;


                                }

                            }
                        }
                    }
                    if (count($new_pages) > 0) {
                        addPagesForMetricCheck($new_pages);
                    }
                }

                if (count($invalid_urls) > 0 || count($ban_urls) > 0 || count($duplicate_urls) || count($deleted_urls) > 0) {
                    $msg = "";
                    if (count($invalid_urls) > 0) {
                        $msg .= '<strong>These URLs not found on your blog : </strong><br />';
                        $msg .= implode("<br />", $invalid_urls);
                    }
                    if (count($ban_urls) > 0) {
                        $msg .= "<br /><strong>These URLs listed as banned : </strong><br />";
                        $msg .= implode("<br />", $ban_urls);
                    }
                    if (count($duplicate_urls) > 0) {
                        $msg .= "<br /><strong>These URLs already in Database : </strong><br />";
                        $msg .= implode("<br />", $duplicate_urls);
                    }
                    if (count($deleted_urls) > 0) {
                        $msg .= "<br /><strong>These URLs has been deleted : </strong><br />";
                        $msg .= implode("<br />", $deleted_urls);
                    }
                    return response()->json(['errors' => ['msg' => $msg]], 422);
                }
                return response()->json(['status' => 'success', 'product' => 'all'], 200);
            }
            return response()->json(['errors' => ['msg' => 'No Blog Posts found on your Blog']], 422);
        }

    }

    public function deletePage($id, Request $request)
    {
        $page = Page::where('seller_id', \Auth::user()->id)->where('id', $id)->first();
        if ($page) {

            $page->delete();
            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }
}
