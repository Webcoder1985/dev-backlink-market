<?php

namespace App\Http\Controllers\Member\API;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use App\Models\PageCategory;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('site.access');
    }
    public function siteKeyUpdateStatus(Request $request){

        $validator = Validator::make($request->all(), [
            'auth_key' => 'required',
            'site_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = $request->all();
        $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->where('deleted_at', '=', NULL)->first();
        //Log::info(print_r($seller_site->update_remote_authkey_allowed, true));
        if ($seller_site->update_remote_authkey_allowed == true) {
            return response()->json(['status' => 'allowed'], 200);
        }

        return response()->json(['status' => 'forbid'], 200);
    }

    public function updatePluginVersion(Request $request)
    {
      //  Log::Info(print_r($request->all(),true));
        $validator = Validator::make($request->all(), [
            'auth_key' => 'required',
            'site_url' => 'required|url',
            'plugin_version' => 'required|min:5|max:6' // 1.0.0
        ]);
        if ($validator->fails()) {
      //      Log::Info("Validator failed: ".print_r($validator->errors(),true));
            return response()->json($validator->errors(), 422);
        }
        $data = $request->all();
        $plugin_version = Option::where('option_name', 'plugin_version')->pluck('option_value')->first();
        if($data['plugin_version']!=$plugin_version){
         return response()->json(['status' => 'error', 'message' => 'Installed plugin version '.$data['plugin_version'].' does not match latest version '.$plugin_version], 200);
        }
        $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->where('deleted_at', '=', NULL)->first();
        if ($seller_site) {
            $seller_site->update([
                'plugin_version' => $data['plugin_version']
            ]);
       //       Log::Info("Success updated: ".$seller_site->site_url);
            return response()->json(['status' => 'success'], 200);

        }
       //   Log::Info("No site found: ".$seller_site->site_url);
        return response()->json(['status' => 'error', 'message' => 'No Site found'], 200);
    }

    public function products(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'page_id' => 'required|integer',
            'page_url' => 'required|url',
            'auth_key' => 'required',
            'site_url' => 'required|url',
            'is_active' => 'required|boolean',
            'content' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|string',
            'publish_date' => 'required|date',
            'tags' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = $request->all();
        //$categories = get_categories_using_content(strip_tags($data['content']));
        //$language = get_language_using_content(strip_tags($data['content']));
        $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->where('is_active', '=', 1)->first();
        $tags_array=array();
        $tags="0";


        if($data["tags"]!=false){
           foreach ($data["tags"] as $value){
               $tags_array[]=$value["name"];
           }
            if(count($tags_array)>0){
            $tags=implode(",",$tags_array);
            }
        }
        $content=strip_tags($data['content']);
       $plain=str_replace( array( "'",'"' ),'',$content);
        if($seller_site)
        {
            $page = Page::updateOrCreate([
                'seller_site_id' => $seller_site->id,
                'seller_site_page_id' => $data['page_id']
            ], [
                'seller_id' => $seller_site->seller_id,
                'seller_site_id' => $seller_site->id,
                'seller_site_url' => $data['site_url'],
                'seller_site_page_id' => $data['page_id'],
                'seller_site_page_url' => $data['page_url'],
                'content' => $plain,
                'is_active' => $data['is_active'],
                'title' => $data['title'],
                'type' => $data['type'],
                'publish_date' => $data['publish_date'],
                'tags' => $tags,
                'tld' => $seller_site->tld,
                'country' => $seller_site->country
            ]);
            //DB::table('blmkt_page_category')->where('page_id', $page->id)->delete();
            //print_r($categories); exit;
            /*if(count($categories) > 0)
            {
                foreach($categories as $category)
                {
                    $page_categoory = PageCategory::create([
                        'page_id' => $page->id,
                        'category' => $category,
                    ]);
                }
            }*/

            return response()->json(['status' => 'success', 'product' => $page], 200);
        }
        return response()->json(['status' => 'success', 'product' => []], 200);
    }

    public function productsBulk(Request $requestBulk)
    {


        $seller_site = SellerSite::where('site_url', '=', $requestBulk[0]['site_url'])->where('site_auth_key', '=', $requestBulk[0]['auth_key'])->where('is_active', '=', 1)->first();


        foreach ($requestBulk->request->all() as $element) {

            $validator = Validator::make($element, [
                'ID' => 'required|integer',
                'url' => 'required|url',
                'auth_key' => 'required',
                'site_url' => 'required|url',
                'is_active' => 'required|boolean',
                'content' => 'required|string',
                'title' => 'required|string',
                'type' => 'required|string',
                'publish_date' => 'required|date',
                'tags' => 'required'
            ]);

            if ($validator->fails()) {
                Log::error($validator->errors());
                return response()->json($validator->errors(), 422);
            } else {


                $tags_array = array();
                $tags = "0";


                if ($element["tags"] != false) {
                    foreach ($element["tags"] as $value) {
                        $tags_array[] = $value["name"];
                    }
                    if (count($tags_array) > 0) {
                        $tags = implode(",", $tags_array);
                    }
                }
                $content=strip_tags($element['content']);
               $plain=str_replace( array( "'",'"' ),'',$content);
                if ($seller_site) {
                    $page = Page::updateOrCreate([
                        'seller_site_id' => $seller_site->id,
                        'seller_site_page_id' => $element['ID']
                    ], [
                        'seller_id' => $seller_site->seller_id,
                        'seller_site_id' => $seller_site->id,
                        'seller_site_url' => $element['site_url'],
                        'seller_site_page_id' => $element['ID'],
                        'seller_site_page_url' => $element['url'],
                        'content' => $plain,
                        'is_active' => $element['is_active'],
                        'title' => $element['title'],
                        'type' => $element['type'],
                        'publish_date' => $element['publish_date'],
                        'tags' => $tags,
                        'tld' => $seller_site->tld,
                        'country' => $seller_site->country
                    ]);
                    $pages[] = $page;
                }
            }
        }

        return response()->json(['status' => 'success'], 200);

    }
    public function productChangeState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required|integer',
            'auth_key' => 'required',
            'site_url' => 'required|url',
            'is_active' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = $request->all();
        $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->where('is_active', '=', 1)->first();
        if($seller_site)
        {
            $page = Page::where('seller_site_id', '=', $seller_site->id)->where('seller_site_page_id', '=', $data['page_id'])->first();
            if($page)
            {
                $page->update([
                    'is_active' => $data['is_active'],
                ]);
            }
            return response()->json(['status' => 'success', 'product' => $page], 200);
        }
        return response()->json(['status' => 'success', 'product' => []], 200);
    }

    public function productsGetStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required|integer',
            'auth_key' => 'required',
            'site_url' => 'required|url'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = $request->all();
        $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->first();
        if($seller_site)
        {
            $page = Page::where('seller_site_id', '=', $seller_site->id)->where('seller_site_page_id', '=', $data['page_id'])->first();
            if($page)
            {
              return response()->json(['status' => 'success', 'product' => $page], 200);
            }
        }
        return response()->json(['status' => 'success', 'product' => []], 200);
    }

    public function checkOrdered(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'page_id' => 'required|integer',
          'auth_key' => 'required',
          'site_url' => 'required|url'
      ]);
      if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
      }

      $data = $request->all();
      $seller_site = SellerSite::where('site_url', '=', $data['site_url'])->where('site_auth_key', '=', $data['auth_key'])->first();
      if($seller_site)
      {
        $contentStr="";
        //echo $seller_site->id;
        $page = Page::where('seller_site_id', '=', $seller_site->id)->where('seller_site_page_id', '=', $data['page_id'])->first();
        //var_dump($page);
        if($page)
        {
          $orderDetails = OrderDetail::where('page_id','=',$page->id)->get();
          //echo $orderDetails->page_url;
          //var_dump($orderDetails);
          if($orderDetails)
          {
            $contentStr.="<div class='backlink-data'><ul>";
            //count($orderDetails);
            foreach ($orderDetails as $orderDetail) {
                $nofollow="";
                if($orderDetail->no_follow==1){$nofollow=' rel="nofollow"';}
               $contentStr.= preg_replace('/' . preg_quote($orderDetail->anchor_text,"/") . '/i', '<a href="' . $orderDetail->promoted_url . '"  target="_blank"'.$nofollow.'>' . $orderDetail->anchor_text . '</a>', $orderDetail->link_content, 1);
            //  $contentStr.="<li>".$orderDetail->link_content." <a href='".$orderDetail->promoted_url."' target='_blank'>".$orderDetail->anchor_text."</a> ".$orderDetail->content_after_anchor_text."</li>";
            }
            $contentStr.="</ul></div>";
            return response()->json(['status' => 'success', 'content' => $contentStr], 200);
          }
        }
        //return response()->json(['status' => 'success', 'product' => []], 200);

      }
      return response()->json(['status' => 'success', 'content' => ''], 200);



    }


}
