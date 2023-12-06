<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Jobs\CheckSitePagesIndexed;
use App\Jobs\FindPageCategories;
use App\Jobs\AddPageDigiMetr;
use App\Models\Batch;
use Storage;
use App\Jobs\ProcessBulkPageMyAddrAPI;
use App\Jobs\CalculatePageOBLJob;

use Ibericode\Vat;
use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return DataTables::eloquent(User::query())
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('fID'))) {
                        $query->Where('id', $request->get('fID'));
                    }
                    if (!empty($request->get('fEmail'))) {
                        $query->Where('email', 'like', '%' . $request->get('fEmail') . '%');
                    }
                    if (!empty($request->get('fFirstName'))) {
                        $query->Where('firstname', 'like', '%' . $request->get('fFirstName') . '%');
                    }
                    if (!empty($request->get('fLastName'))) {
                        $query->Where('lastname', 'like', '%' . $request->get('fLastName') . '%');
                    }
                    if (!empty($request->get('fCountry'))) {
                        $query->WhereIn('country', $request->get('fCountry'));
                    }

                    if (!empty($request->get('columns'))) {
                        $searchKeyword = $request->get('search');
                        if (isset($searchKeyword) && !empty($searchKeyword) && !empty($searchKeyword['value'])) {
                            $query->where(function ($query) use ($searchKeyword) {
                                $query->orWhere('id', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('firstname', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('lastname', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('email', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('country', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('user_status', 'like', "%{$searchKeyword['value']}%");
                            });
                        }
                    }
                })
                ->editColumn('action', function ($data) {
                    return '
                    <button  onclick="editUser(' . $data->id . ')" class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                    <button  onclick="banUser(' . $data->id . ')" class="btn btn-info btn-sm"><i class="zmdi zmdi-block"></i></button>
                    <button  onclick="deleteUser(' . $data->id . ')" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>';
                })
                ->editColumn('created_at', function ($data) {
                    $date = Carbon::parse($data->created_at);
                    return $date->format('d.m.Y');
                })
                ->editColumn('user_status', function ($data) {
                    if ($data->user_status === 0) {
                        return 'Registered';
                    } elseif ($data->user_status === 1) {
                        return 'Email Verified';
                    } elseif ($data->user_status === 2) {
                        return 'Banned';
                    } elseif ($data->user_status === 3) {
                        return 'Admin';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $countries = User::pluck('country');
        $countryLists = getCountry();
        return view('member.users', compact('countries', 'countryLists'));
    }

    public function ChangeStatus(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $user = User::find($request->id);
            $user->user_status = $request->status;
            $user->update();
        }
    }

    public function deleteUser(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $user = User::find($request->id);
            $user->delete();
        }
    }

    public function getUser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        return response()->json($user);
    }

    public function saveUser(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $data = $request->all();

            if (isset($data['password']) & !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            if (isset($data['user_status']) & !empty($data['user_status']) & $data['user_status'] === "1") {
                $data['email_verified_at'] = date('Y-m-d H:i:s');
            }
            unset($data['_token']);
            $user = User::where('id', $id)->update($data);
        }
    }

    public function sellerSites(Request $request)
    {
        //$test=DB::table("blmkt_sites as a")->leftJoin("blmkt_pages as b", function($join){	$join->on("a.id", "=", "b.seller_site_id");})->select("a.*", SellerSite::raw("COUNT('blmkt_pages.id') as pages_amount"))->where("a.seller_id", "=", 6)->groupBy("a.id")->get();

        if ($request->ajax()) {
            //return DataTables::eloquent(DB::table("blmkt_sites as a")->leftJoin("blmkt_pages as b", function($join){	$join->on("a.id", "=", "b.seller_site_id");})->select("a.*", DB::raw("COUNT('blmkt_pages.id') as pages_amount"))->where("a.seller_id", "=", Auth::user()->id)->groupBy("a.id")->get())
            return DataTables::eloquent(SellerSite::select('*')->where('seller_id', Auth::user()->id))
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('columns'))) {
                        $searchKeyword = $request->get('search');
                        if (isset($searchKeyword) && !empty($searchKeyword) && !empty($searchKeyword['value'])) {
                            $query->where(function ($query) use ($searchKeyword) {
                                $query->orWhere('site_url', 'like', "%{$searchKeyword['value']}%")
                                    ->orWhere('is_active', 'like', "%{$searchKeyword['value']}%");
                            });
                        }
                    }
                })
                ->editColumn('action', function ($data) {
                    $actions = '';
                    if ($data->is_active === 0) {
                        $actions .= '<button  onclick="confirmSite(this)" data-auth-key="' . $data->site_auth_key . '"  data-id="' . $data->id . '" data-site="' . $data->site_url . '"data-active="' . $data->is_active . '"  class="btn btn-warning btn-sm">Confirm AuthKey</button>';
                    }
                    if ($data->is_active === 1) {
                        $actions .= '
                <a href=' . URL('site_pages/' . $data->id) . ' style="margin-top:5px;" class="btn btn-primary btn-sm">Pages</a> ';
                        // <a href=' . URL('seller_pages/' . $data->id . '/?recheck_pages=true') . ' style="margin-top:5px" class="btn btn-primary btn-sm">New Pages?</a>';
                    }
                    $actions .= '
                <button  onclick="editSite(this)" data-id="' . $data->id . '" data-site="' . $data->site_url . '"data-active="' . $data->is_active . '"  class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                <button   onclick="deleteSite(' . $data->id . ')" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                ';
                    return $actions;
                })
                ->editColumn('is_active', function ($data) {
                    if ($data->is_active === 0) {
                        return '<button  onclick="confirmSite(this)" data-auth-key="' . $data->site_auth_key . '"  data-id="' . $data->id . '" data-site="' . $data->site_url . '"data-active="' . $data->is_active . '"  class="btn btn-warning btn-sm">Waiting for AuthKey Confirmation</button>';
                    } elseif ($data->is_active === 1) {
                        return 'Yes';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    $date = Carbon::parse($data->created_at);
                    return $date->format('d.m.Y');
                })
                ->editColumn('pages', function ($data) {
                    $pages = Page::where("seller_site_id", $data->id)->get();
                    $active_pages = Page::where("seller_site_id", $data->id)->where('is_active', 1)->get();
                    return count($active_pages) . "/" . count($pages);
                })
                ->editColumn('plugin_version', function ($data) {
                    if(isNull($data->plugin_version)) {
                        return '<div class="text-center">-</div>';
                    } else {
                        $plugin_version = Option::where('option_name', 'plugin_version')->pluck('option_value')->first();
                        if ($plugin_version == $data->plugin_version) {
                            return '<span title="Latest version. No updates required.">' . $data->plugin_version . '</span>';
                        } else {

                            return '<a style="color:#fff !important" href="' . $data->site_url . '/wp-admin/plugins.php" class="btn btn-danger" target="_blank" title="Your Version: ' . $data->plugin_version . ' | Latest Version: ' . $plugin_version . '">Update Available</a><button title="Re-Check Blog Version Number." id="update_btn" onclick="recheckVersion(' . $data->id . ')" class="btn btn-warning btn-md"><i id="recheck_icon" style="font-size: 17px" class="zmdi zmdi-rotate-right zmdi-hc-lg"></i>
            </button>';
                        }
                    }
                })
                ->rawColumns(['action', 'is_active', 'plugin_version'])
                ->make(true);
        }
        return view('member.seller_sites');
    }

    public function saveSites(Request $request)
    {

        $client = new \GuzzleHttp\Client(['verify' => false, 'http_errors' => false]);
        /*$response = $client->request('GET', $url);
        print_r($response);
        echo "--";
        $content = $response->getBody();
        print_r($content);*/

        // Get the last redirect URL or the URL of the request that received
        // this response


        $request->validate([
            'site_url' => 'required|url',

        ], ['is_active' => 'Status']);

        $data = $request->all();


        $url = $data['site_url'];

        $parse_url = parse_url($url);
        $parse_url['host'] = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));

        if(isset($parse_url['path'])){
            if (substr($parse_url['path'], -1) == "/") {
                $parse_url['path'] = substr($parse_url['path'], 0, -1);
            }
        } else $parse_url['path']="";

        if (isset($data['id'])) {
            $found_site = SellerSite::where('site_url', 'like', '%' . $parse_url['host'] . $parse_url['path'])->where('id', '!=', $data['id'])->first();
            print_r($found_site);
        } else
            $found_site = SellerSite::where('site_url', 'like', '%' . $parse_url['host'] . $parse_url['path'])->first();
        if ($found_site) {
            $response['success'] = false;
            $response['message'] = '<span class="error">That site has already been listed on the Backlink Market.</span><span><br><br> <a style="color:#019BDB;" href="https://www.backlink-market.com/contact" target="_blank">Contact us</a> if you think this is a mistake.</span>';
            return json_encode($response);
        }

        if (substr($url, -1) == "/") {
            $url = $url = substr($url, 0, -1);
            $data['site_url'] = $url;
        }
        try {
            $request_site = $client->get($url, [
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$final_url) {
                    $final_url = $stats->getEffectiveUri();
                    if (substr($final_url, -1) == "/") {
                        $final_url = $url = substr($final_url, 0, -1);
                    }
                }
            ]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $response['success'] = false;
            $response['message'] = "<span class='error'>Can't get access to your Blog.</div>";
            return json_encode($response);
        }
        //echo $final_url."-".$url;
        if ($final_url != $url) {
            $response['success'] = false;
            $response['redirecturl'] = (string)$final_url;
            $response['message'] = "<span class='error'>The website is redirecting to " . $final_url . "</span>";

            return json_encode($response);
        }


        try {
            $request_site = $client->get($url . "/wp-admin/", [
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$final_url) {
                    $final_url = $stats->getEffectiveUri();
                }
            ]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $response['success'] = false;
            $response['message'] = "<span class='error'>Can't get access website.</div>";
            return json_encode($response);
        }
        if (!str_contains($final_url, "/wp-login.php")) {
            $response['success'] = false;
            $response['redirecturl'] = (string)$url;
            $response['message'] = "<span class='error'>We can not find a Wordpress Blog at this URL!</span><span><br><br>Please double check your input.<br><a style=\"color:#019BDB;\" href=\"https://www.backlink-market.com/contact\" target=\"_blank\">Contact us</a> if you think this is a mistake.</span>";
            return json_encode($response);
        }


        unset($data['_token']);
        if (isset($data['id'])) {
            $id = $data['id'];
            SellerSite::where('id', $id)->update($data);
            return '{}';
        }
        $data['site_auth_key'] = Str::random(12);
        $data['seller_id'] = Auth::user()->id;
        $data['site_id'] = SellerSite::create($data)->id;
        $data['_token'] = csrf_token();
        return json_encode($data);
    }

    public function verifySite(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $site = SellerSite::find($data['site_id']);
        $url = $site->site_url . "/wp-json/wp/v2/verify-domain";
        $client = new \GuzzleHttp\Client(['verify' => false]);
        try {

            $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $data['auth_key']]]);
            $content = json_decode($response->getBody(), true);
            //print_r($content);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response['valid'] = false;
            $response['validreason'] = "Verification failed. Add AuthKey to your Blog.";
            Log::info('verifySite Guzzle catch error:'.$e->getMessage());
            return json_encode($response);
        }
        $return = array();
        //echo $content['key']."-".$data['auth_key'];
        if ($content['key'] == $data['auth_key']) {
            $return['valid'] = true;
            $site = SellerSite::find($data['site_id']);
            $site->site_url = $content['site_url'];
            $site->is_active = true;
            $site->plugin_version = $content['plugin_version'];
            $site->update();
        } else {
            $return['valid'] = false;
            $response['validreason'] = "AuthKey is incorrect.";
        }
        return json_encode($return);
    }

    public function deleteSite(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $site = SellerSite::find($request->id);
            $site->delete();
            $this->deleteSitesPages($request);
        }
    }

    public function deleteSitesPages(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            Page::where("seller_site_id", $id)->delete();
        }
    }

    public function getSellerSitePagesAmount($id)
    {

        $sites = SellerPage::site($id);
        return count($sites);
    }

    public function firstSync($id)
    {
        $site = SellerSite::find($id);
        $sync_url = $site->site_url . "/wp-json/wp/v2/first-sync";
        $client = new \GuzzleHttp\Client(['verify' => false]);
        try {
            $response = $client->request('GET', $sync_url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
            $content = json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return false;
        }
        return $content;

    }

    public function addPageToDatabase($data, $site_id)
    {

        $validator = Validator::make($data, [
            'ID' => 'required|integer',
            'type' => 'required|string',
            'title' => 'required|string',
            'url' => 'required|url',
            'publish_date' => 'required|date',
            'content' => 'required|string',
            'tags' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //$category = get_categories_using_content(strip_tags($data['content']));
        //$language = get_language_using_content(strip_tags($data['content']));
        $seller_site = SellerSite::find($site_id);
        $tags_array = array();
        $tags = "0";
        if ($data["tags"] != false) {
            foreach ($data["tags"] as $value) {
                $tags_array[] = $value["name"];
            }
            if (count($tags_array) > 0) {
                $tags = implode(",", $tags_array);
            }
        }

        $content = strip_tags($data['content']);
        $plain = str_replace(array("'", '"'), '', $content);
        if ($seller_site) {
            $page = Page::updateOrCreate([
                'seller_site_id' => $seller_site->id,
                'seller_site_page_id' => $data['ID']
            ], [
                'seller_id' => $seller_site->seller_id,
                'seller_site_id' => $seller_site->id,
                'seller_site_url' => $seller_site->site_url,
                'seller_site_page_id' => $data['ID'],
                'seller_site_page_url' => $data['url'],
                'content' => $plain,
                'is_active' => 0,
                'type' => $data['type'],
                'publish_date' => $data['publish_date'],
                //'category' => $category,
                //'language' => $language,
                'title' => $data['title'],
                'tags' => $tags,
                'tld' => $seller_site->tld,
                'country' => $seller_site->country
            ]);

            return response()->json(['status' => 'success', 'product' => $page], 200);
        }
        return response()->json(['status' => 'failed', 'product' => []], 200);
    }

    public function recheckVersion($id)
    {
        //Initiate a Blog to marketplace version post. If newer, the plugin version gets updated in database for sites table
        $site = SellerSite::where('id', '=', $id)->where('seller_id', '=', \Auth::user()->id)->first();
        if (!isset($site->site_url)) {
            //   Log::info('Site not found');
            return false;
        }
        $url = $site->site_url . "/wp-json/wp/v2/version-update-request";
        $client = new \GuzzleHttp\Client(['verify' => false]);

        try {
            $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
            $response_json = json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::info($url . "\n" . $e->getResponse()->getBody()->getContents());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::info($url . "\n" . $e->getResponse()->getBody()->getContents());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
        return $response_json;
    }

    public function recheckForPages($id)
    {

        $site = SellerSite::where('id', '=', $id)->where('seller_id', '=', \Auth::user()->id)->first();
        if (!isset($site->site_url)) {
            return view('member.seller_pages', ['all_pages' => "Access Permission! You are not allowed to recheck this site.", 'site_id' => $id]);
        }
        $url = $site->site_url . "/wp-json/wp/v2/get-posts-pages";
        $client = new \GuzzleHttp\Client(['verify' => false]);
        try {
            $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
            $all_pages = json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return view('member.seller_pages', ['all_pages' => $e->getMessage(), 'site_id' => $id]);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return view('member.seller_pages', ['all_pages' => $e->getMessage(), 'site_id' => $id]);
        }
        if (!isset($all_pages)) {
            $all_pages = "Can't connect to your Blog. Please make sure the Plugin is installed, activated and AuthKey is correct.";
        } else {
            $new_page_ids = array_column($all_pages, 'ID');
            $all_db_pages = Page::select("seller_site_page_id")->where('seller_site_id', '=', $id)->where('seller_id', '=', \Auth::user()->id)->get()->toArray();
            $new_pages = array();
            if (count($all_db_pages) > 0) {
                $all_db_pages = array_column($all_db_pages, 'seller_site_page_id');
                foreach ($all_pages as $pages) {
                    if (!in_array($pages["ID"], $all_db_pages)) {
                        $new_pages[] = $pages;
                    }
                }
                if (count($new_pages) == 0) {
                    return view('member.seller_pages', ['all_pages' => "No new pages detected. If you think this is a mistake contact us.", 'site_id' => $id]);
                }


            } else {
                $new_pages = $all_pages;
            }

            foreach ($new_pages as $new) {
                $result = $this->addPageToDatabase($new, $id);
                if ($result->getData("status")["status"] == "success") {
                    $new_pages_successfull[] = $new;
                }
            }

            return view('member.seller_pages', ['all_pages' => $new_pages, 'site_id' => $id]);
        }

        return view('member.seller_pages', ['all_pages' => $all_pages, 'site_id' => $id]);

    }

    public function sellerPages($id)
    {
    
        if (SellerSite::where("id", "=", $id)->where("seller_id", "=", \Auth::user()->id)->count() == 0) {
            $all_pages = "Invalid Access detected! Not your Site!";
            return view('member.seller_pages', ['all_pages' => $all_pages, 'site_id' => $id]);
        }
        if (isset($_GET["firstSync"]) && $_GET["firstSync"] == true) {
            $all_pages = $this->firstSync($id);
            //$all_pages = Page::all(['*','id AS ID', 'created_at AS date'])->where('seller_site_id','=',$id)->where('seller_id', '=', Auth::user()->id);

            return view('member.seller_pages', ['all_pages' => $all_pages, 'site_id' => $id]);
        }


        $site = SellerSite::find($id);
        $url = $site->site_url . "/wp-json/wp/v2/get-posts-pages";
        $client = new \GuzzleHttp\Client(['verify' => false]);
        try {
            $response = $client->request('GET', $url, ['headers' => ['Siteauthkey' => $site->site_auth_key]]);
            $all_pages = json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {

        }
        if (!isset($all_pages)) {
            $all_pages = "Can't connect to your Blog. Please make sure the Plugin is installed, activated and AuthKey is correct.";
        }
        return view('member.seller_pages', ['all_pages' => $all_pages, 'site_id' => $id]);

    }

    /*public function sellerPagesRefresh($id){
      $site = SellerSite::find($id);

      $url=$site->site_url."/wp-json/wp/v2/get-posts-pages";
      $client = new \GuzzleHttp\Client(['verify' => false ]);
      try{
        $response = $client->request('GET', $url);
        $all_pages = json_decode($response->getBody(), true);
      }
      catch(\GuzzleHttp\Exception\ClientException $e)
      {

      }
      foreach($all_pages as $all_page){
        $page=Page::where('seller_site_id',$id)->where('seller_site_page_id',$all_page['ID']);
        if($page)
          echo "Page exist:".$all_page['ID']."<br />";
        else
          echo "Page Not exist:".$all_page['ID']."<br />";
      }
      print_r($all_pages); exit;

      return view('member.seller_pages',['all_pages' => $all_pages,'site_id' => $id]);

    }*/

    public function sellerPagesSave($id, Request $request)
    {
        $data = $request->all();
        // why deactiveate ids here?
        Page::where('seller_site_id', '=', $data['site_id'])->update(['is_active' => 0]);
        if (isset($data['selected_pages'])) {
            foreach ($data['selected_pages'] as $selected_page) {
                $page = Page::where('seller_id', Auth::user()->id)->where('seller_site_id', $data['site_id'])->where('seller_site_page_id', $selected_page)->first();
                //print_r($page);
                if ($page) {
                    $page->is_active = 1;
                    $page->update();
                }
            }
        }

        $site = SellerSite::find($data['site_id']);
        addSiteForMetricCheck($site->id);
        return redirect()->route('site_pages', [$id]);
    }


    public function editProfile()
    {
        $user = User::find(Auth::user()->id);
        $countryLists = getCountry();
        $eucountry = 0;
        foreach ($countryLists as $countryList) {
            if ($user['country'] == $countryList['code']) {
                if ($countryList['eucountry'] == 1) {
                    $eucountry = 1;
                    break;
                }
            }
        }

        return view('member.edit_profile', [
            'user' => $user,
            'countryLists' => $countryLists,
            'eucountry' => $eucountry
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'street' => 'required|string',
            'street_number' => 'required|max:10',
            'zip' => 'required|max:10',
            'city' => 'required|string',
            'country' => 'required|max:2|string',
            /*'password' => 'required|min:8|string', */ // It shouldn't be required
            'paypal_email' => 'email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_message', $validator->errors());
        }

        $user = User::find(\Auth::user()->id);
        // VAT CHECK
        $countries = new Vat\Countries();
        if (!$countries->isCountryCodeInEU($data['country'])) {
            $data['vat'] = "";
            $user->vat = "";
        }
        if (isset($data['vat']) && !empty($data['vat'])) {
            $vat_country_code = substr(strtoupper($data['vat']), 0, 2);
            $vat_validator = new Vat\Validator();
            if ($vat_country_code != strtoupper($data['country'])) {
                $validator->errors()->add('vat', 'VAT number does not match your selected country');
                return redirect()->back()->with('error_message', $validator->errors());
            }
            if ($user->vat !== $data['vat']) {

                if (!$vat_validator->validateVatNumberFormat($data['vat'])) {
                    $validator->errors()->add('vat', 'Incorrect VAT number format');
                    return redirect()->back()->with('error_message', $validator->errors());
                }
                if (!$vat_validator->validateVatNumber($data['vat'])) {
                    $validator->errors()->add('vat', 'VAT number invalid');
                    return redirect()->back()->with('error_message', $validator->errors());
                }
            }
        }

        if ($validator->fails()) {
            return redirect()->back()->with('error_message', $validator->errors());
        }
        if ($user->paypal_email !== $data['paypal_email']) {
            $user->paypal_email = $data['paypal_email'];
        }
        if (isset($data['password']) & !empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if ($user->firstname !== $data['firstname']) {
            $user->firstname = $data['firstname'];
        }
        if ($user->lastname !== $data['lastname']) {
            $user->lastname = $data['lastname'];
        }
        if ($user->street !== $data['street']) {
            $user->street = $data['street'];
        }
        if ($user->street_number !== $data['street_number']) {
            $user->street_number = $data['street_number'];
        }
        if ($user->zip !== $data['zip']) {
            $user->zip = $data['zip'];
        }
        if ($user->city !== $data['city']) {
            $user->city = $data['city'];
        }
        if ($user->country !== $data['country']) {
            $user->country = $data['country'];
        }
        if ($user->vat !== $data['vat']) {
            $user->vat = $data['vat'];
        }
        if ($user->vat !== "") {
            if (isset($data['kleinunternehmer']) && $data['kleinunternehmer'] == "on") {
                $user->kleinunternehmer = 1;
            } else {
                $user->kleinunternehmer = 0;
            }
        }
        if ($user->vat == "") {
            $user->kleinunternehmer = 0;
        }

        $user->update();
        unset($data['_token']);
        return redirect()->back()->with('message', 'Profile Updated!');
    }

    public function notificationChecked()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->last_notification_check = \Carbon\Carbon::now();
        $user->update();
        $response = ['success' => true];
        return json_encode($response);
    }
}
