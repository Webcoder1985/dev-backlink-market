<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Category;
use App\Models\PageCategory;
use App\Models\OrderDetail;
use DataTables;
use Illuminate\Support\Facades\RateLimiter;
use OpenAI\Client;
use Illuminate\Support\Facades\Log;

class MarketPlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banSites = SellerSite::where('is_ban', '=', 1)->pluck('id')->toArray();
            $pages = Page::where('is_active', '=', 1)->where('is_ban', '=', 0)->where('page_price_buyer', '>', 0)->whereNull('deleted_at');
            $result = DataTables::eloquent($pages)
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
                        if (isset($searchVal[14]) && !empty($searchVal[14]['search']['value'])) {
                            $keywords = explode(' ', $searchVal[14]['search']['value']);
                            if (!empty($keywords)) {
                                $search_by = "title";
                                if (isset($searchVal[15]) && !empty($searchVal[15]['search']['value'])) {
                                    if ($searchVal[15]['search']['value'] == "post")
                                        $search_by = "content";
                                }
                                $query->where(function ($query) use ($keywords, $search_by) {
                                    foreach ($keywords as $keyword) {
                                        $query->Where($search_by, 'like', '%' . trim($keyword) . '%');
                                    }
                                });
                            }
                        }

                        if (isset($searchVal[1]) && !empty($searchVal[1]['search']['value'])) {
                            $data = explode(',', $searchVal[1]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('moz_da', '>=', $data[0]);
                                    $query->where('moz_da', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[2]) && !empty($searchVal[2]['search']['value'])) {
                            $data = explode(',', $searchVal[2]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('moz_pa', '>=', $data[0]);
                                    $query->where('moz_pa', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[3]) && !empty($searchVal[3]['search']['value'])) {
                            $data = explode(',', $searchVal[3]['search']['value']);
                            if (!empty($data)) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('maj_tf', '>=', $data[0]);
                                    $query->where('maj_tf', '<=', $data[1]);
                                });
                            }
                        }

                        if (isset($searchVal[4]) && !empty($searchVal[4]['search']['value'])) {
                            $data = explode(',', $searchVal[4]['search']['value']);
                            if (!empty($data)) {
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


                        if (isset($searchVal[12]) && !empty($searchVal[12]['search']['value'])) {
                            $data = explode(',', $searchVal[12]['search']['value']);
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
                                //print_r($data);
                                //$page_ids=PageCategory::select(['page_id'])->whereIn('category',$data)->get();
                                $page_ids = PageCategory::select('page_id')
                                    ->Where(function ($query) use ($data) {
                                        for ($i = 0; $i < count($data); $i++) {
                                            $query->orwhere('category', 'like', $data[$i] . '%');
                                        }
                                    })->get(['page_id'])->map(function ($item) {
                                        return array_values($item->toArray());
                                    });;
                                $query->whereIn('id', $page_ids);
                            }
                        }

                        if (isset($searchVal[8]) && !empty($searchVal[8]['search']['value'])) {
                            $data = explode(',', $searchVal[8]['search']['value']);
                            if (!empty($data) && !in_array('all', $data)) {
                                $query->whereIn('country', $data);
                            }
                        }
                        $query->where('is_active', 1);

                    }

                })
                ->editColumn('category', function ($data) {
                    $page_categories = PageCategory::where('page_id', '=', $data->id)->get();
                    //return var_dump($page_categories);
                    $page_categories_title = "";
                    foreach ($page_categories as $page_category) {
                        //$page_categories_title.=$page_category->category_title->title.",";
                        if (isset($page_category->category_title))
                            $page_categories_title .= $page_category->category_title->title . ",";
                    }
                    $page_categories_title = rtrim($page_categories_title, ",");
                    return $page_categories_title;
                })
                ->editColumn('page_price_buyer', function ($data) {
                    return \Config::get('app.currency_symbol') . $data->page_price_buyer;
                })
                ->editColumn('is_active', function ($data) {
                    $html = '';
                    if ($data->is_active) {
                        $html = '<span class="badge badge-success">Yes</span>';
                    } else {
                        $html = '<span class="badge badge-danger">No</span>';
                    }
                    return $html;
                })
                ->editColumn('actions', function ($data) {
                    $carts = \Cart::content();
                    //$html = '';

                    $checked = '';
                    if (count($carts) > 0) {
                        foreach ($carts as $cart) {
                            if ($data->id == $cart->id) {
                                $checked = true;
                            }
                        }
                        if ($checked) {
                            return '<span id="action_' . $data->id . '"><a href="javascript:void(0)" onclick="deletecartitem_ajax(\'' . $cart->rowId . '\',\'' . $data->id . '\',\'' . $data->page_price_buyer . '\');">Remove from Cart</a></span>';
                        } else {
                            return '<span id="action_' . $data->id . '"><a href="javascript:void(0)" class="pr_chk" data-id = "' . $data->id . '" data-price="' . $data->page_price_buyer . '">Add to Cart</a></span>';
                        }
                        //$disable = '';
                    } else {
                        return '<span id="action_' . $data->id . '"><a href="javascript:void(0)" class="pr_chk" data-id = "' . $data->id . '" data-price="' . $data->page_price_buyer . '">Add to Cart</a></span>';
                    }

                    //return '<input type="checkbox" class="pr_chk" data-id = "'.$data->id.'" data-price="'.$data->page_price_buyer.'" name="product_id[]" value="'.$data->id.'" '.$disable.' '.$checked.'>

                })
                ->rawColumns(['page_price_buyer', 'is_active', 'actions', 'title'])
                ->make(true);
            return $result;
        }
        // if (\Auth::user()->user_status == 3) {
        $da_min = Page::min('moz_da');
        $da_max = Page::max('moz_da');
        $pa_min = Page::min('moz_pa');
        $pa_max = Page::max('moz_pa');
        $tf_min = Page::min('maj_tf');
        $tf_max = Page::max('maj_tf');
        $cf_min = Page::min('maj_cf');
        $cf_max = Page::max('maj_cf');
        $bl_min = Page::min('maj_bl');
        $bl_max = Page::max('maj_bl');
        $obl_min = Page::min('obl');
        $obl_max = Page::max('obl');
        $price_min = Page::min('page_price_buyer');
        $price_max = Page::max('page_price_buyer');
        $rd_min = Page::min('rd');
        $rd_max = Page::max('rd');
        $languages = Page::groupBy('language')->select('language')->get();
        $categories = Category::where('parent_level', '=', 0)->get();
        $countries = Page::groupBy('country')->select('country')->get();
        $tlds = Page::groupBy('tld')->select('tld')->get();
        $category_counts = \DB::table('blmkt_page_category')->join('blmkt_pages', 'blmkt_pages.id', '=', 'blmkt_page_category.page_id')->where('is_active', '=', 1)->where('is_ban', '=', 0)->where('page_price_buyer', '>', 0)->where('indexed', '=', 1)->whereNull('deleted_at')->select('category', \DB::raw("count(category) as count"))->groupBy('category')->get();

        $category_counts_array = [];
        foreach ($category_counts as $category_count) {
            $category_counts_array[$category_count->category] = $category_count->count;
        }
        /* } else {
             $da_min = Page::min('moz_da');
             $da_max = Page::max('moz_da');
             $pa_min = Page::min('moz_pa');
             $pa_max = Page::max('moz_pa');
             $tf_min = Page::min('maj_tf');
             $tf_max = Page::max('maj_tf');
             $cf_min = Page::min('maj_cf');
             $cf_max = Page::max('maj_cf');
             $bl_min = Page::min('maj_bl');
             $bl_max = Page::max('maj_bl');
             $obl_min = Page::min('obl');
             $obl_max = Page::max('obl');
             $price_min = Page::min('page_price_buyer');
             $price_max = Page::max('page_price_buyer');
             $rd_min = Page::min('rd');
             $rd_max = Page::max('rd');
             $languages = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('language')->select('language')->get();
             //$categories = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('category')->select('category')->get();
             $categories = Category::where('parent_level', '=', 0)->get();
             $countries = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('country')->select('country')->get();
             $tlds = Page::where('seller_id', '=', \Auth::user()->id)->groupBy('tld')->select('tld')->get();
         } */
        $my_urls = OrderDetail::where("buyer_id", \Auth::user()->id)->groupBy("promoted_url")->pluck('promoted_url')->toArray();
        $my_anchors = OrderDetail::where("buyer_id", \Auth::user()->id)->groupBy("anchor_text")->pluck('anchor_text')->toArray();

        return view('member.marketPlace', compact('da_min', 'da_max', 'pa_min', 'pa_max', 'tf_min', 'tf_max', 'cf_min', 'cf_max', 'bl_min', 'bl_max', 'languages', 'categories', 'tlds', 'price_min', 'price_max', 'obl_min', 'obl_max', 'rd_min', 'rd_max', 'countries', 'my_urls', 'my_anchors', 'category_counts_array'));
    }

    public function sellerPageForm($id = null, Request $request)
    {
        //For Admin access edit button on admin-blog-pages/{id}
        if (\Auth::user()->user_status == 3) {
            $sites = "";
            $page = [];
            if (isset($id) && !empty($id)) {
                $page = Page::where('id', $id)->first();
            }

        } else {
            $sites = SellerSite::where('seller_id', \Auth::user()->id)->select('id', 'site_url')->get();
            $page = [];
            if (isset($id) && !empty($id)) {
                $page = Page::where('seller_id', \Auth::user()->id)->where('id', $id)->first();
            }
        }
        $categories = Category::where('parent_level', '=', 0)->get();
        $category_items = PageCategory::where('page_id', '=', $page->getOriginal("id"))->get();
        foreach ($category_items as $value) {
            $category_ar[] = $value["category"];
        }
        return view('member.seller_page_form', compact('sites', 'page', 'categories', 'category_ar'));
    }

    public function savePage(Request $request)
    {
        $request->validate([
            'seller_site_id' => 'required|exists:blmkt_sites,id',
            'seller_site_page_id' => 'required|integer',
            'seller_site_page_url' => '',
            'category' => 'required',
            'title' => '',
            'page_price_seller' => 'required|numeric|min:1'
        ], [], [
            'seller_site_id' => 'Site ID',
            'seller_site_page_id' => 'Page ID',
            'seller_site_page_url' => 'Page URL',
            'category' => 'Category',
            'title' => 'Title',
            'page_price_seller' => 'Price'
        ]);

        $data = $request->all();
        $page = Page::where('seller_id', \Auth::user()->id)->where('id', $data['id'])->first();
        if (isset($data['id']) && $data['id'] != 0 && $page) {
            $site = SellerSite::find($data['seller_site_id']);
            $page->update([
                //'seller_site_id' => $site->id,
                //  'seller_site_url' => $site->site_url,
                //  'seller_site_page_id' => $data['seller_site_page_id'],
                //  'seller_site_page_url' => $data['seller_site_page_url'],
                'category' => $data['category'],
                //  'title' => $data['title'],
                'page_price_seller' => $data['page_price_seller'],
                // 'page_price_buyer' => $data['page_price_buyer'],
                'is_active' => (isset($data['is_active']) ? 1 : 0),
                //  'indexed' => (isset($data['indexed']) ? 1 : 0),
                //  'last_metric_update_time' => \Carbon\Carbon::now()
            ]);

            return response()->json(['status' => 'success', 'product' => $page], 200);
        } else {
            return response()->json(['errors' => ['msg' => 'Something went wrong! Can not find page']], 422);
            /*
            $site = SellerSite::find($data['seller_site_id']);
            $page = Page::create([
                'seller_id' => \Auth::user()->id,
                'seller_site_id' => $site->id,
                'seller_site_url' => $site->site_url,
                'seller_site_page_id' => $data['seller_site_page_id'],
                'seller_site_page_url' => $data['seller_site_page_url'],
                'category' => $data['category'],
                'title' => $data['title'],
                'page_price_seller' => $data['page_price_seller'],
             // 'page_price_buyer' => $data['page_price_buyer'],
                'is_active' => (isset($data['is_active']) ? 1 : 0),
            //  'indexed' => (isset($data['indexed']) ? 1 : 0),
            //  'last_metric_update_time' => \Carbon\Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'product' => $page], 200);
        */
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function deletePage($id, Request $request)
    {
        //Add validation
        $page = Page::where('seller_id', \Auth::user()->id)->where('id', $id)->first();
        if ($page) {
            if ($page->indexed === 1) {
                $page->delete();
                return response()->json(['success' => 'success'], 200);
            }

        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function getContentAPI(Request $request)
    {
        $request->validate([
            'anchor_text' => 'required|string|max:60|min:1',
            'content_keyword' => 'required|string|max:50|min:1'
        ]);
        if (RateLimiter::remaining('content-generate:' . \Auth::user()->id, $perMinute = 3)) {
            RateLimiter::hit('content-generate:' . \Auth::user()->id);
            $client = \OpenAI::client(env('OPENAI_KEY'));
            $anchor = $request["anchor_text"];
            $content_keyword = $request["content_keyword"];
            $result = $client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => 'create short paragraph in english about "' . $content_keyword . '" with maximum 250 characters\n
                             paragraph must include the words "' . $anchor . '"\n',
                'max_tokens' => 250,
                'temperature' => 0.9,
                'presence_penalty' => 1
            ]);
            /*$data = $request->all();
$anchor = $data["anchor_text"];
$content_keyword = $data["content_keyword"];
$inputContextsString = '{"SECTION_TOPIC_LABEL": "' . $content_keyword . '","SECTION_KEYWORDS_LABEL": "' . $anchor . '"}';
$inputContexts = json_decode($inputContextsString);
$outputs = $this->ryte("607adac76f8fe5000c1e636d", "60572a639bdd4272b8fe358b", "60584cf2c2cdaa000c2a7954", $inputContexts);
return $outputs[0]->text; */
            return trim($result['choices'][0]['text']);
        } else {
            return "Too many requests. Please wait at least " . RateLimiter::availableIn('content-generate:' . \Auth::user()->id) . " seconds";
        }
    }

    public function ryte($languageId, $toneId, $useCaseId, $inputContexts)
    {
        try {
            $endpoint = 'ryte';

            $data = array(
                'languageId' => $languageId,
                'toneId' => $toneId,
                'useCaseId' => $useCaseId,
                'inputContexts' => $inputContexts,
                'variations' => 1,
                'userId' => 'USER1',
                'format' => 'text',
            );

            $response = $this->curl($endpoint, 'post', $data);

            if ($response) {
                $result = json_decode($response);

                return $result->data;
            }
        } catch (Exception $error) {

        }

        return null;
    }

    function curl($endpoint, $method, $data = array())
    {
        $c = curl_init(env('RYTE_URL') . '/' . $endpoint);

        $dataString = json_encode($data);

        $headers = array('Authentication:' . 'Bearer ' . env('RYTE_KEY'));

        if ($method === 'post') {
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $dataString);

            array_push(
                $headers,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString)
            );
        }
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($c);

        curl_close($c);

        return $data;
    }

}
