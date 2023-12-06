<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SellerSite;
use App\Models\GoogleIndexUrl;
use App\Models\Page;
use App\Models\PageMetricHistory;
use Illuminate\Support\Facades\Log;

class GoogleIndexData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:googleindexdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
     public function handle()
     {
       $api = env('SCRAPERAPI');
       $apiurl = env('SCRAPERURL');
       //$this->info("TEST");
         $sites = SellerSite::where('is_active', '=', 1)->get();
         //$this->info(count($sites));exit;
         foreach($sites as $site)
         {
           $ch = curl_init();
		   $site_url = $site['site_url'];
       //$site_url = 'https://www.varnitechnology.com';
           $url = $apiurl.'?api_key='.$api.'&url=https://www.google.com/search?q=site:'.$site_url.'&autoparse=true';
            $curlConfig = array(
                CURLOPT_URL            => $url,
                CURLOPT_POST           => false,
                CURLOPT_RETURNTRANSFER => true
            );
            curl_setopt_array($ch, $curlConfig);
            $result = curl_exec($ch);
            curl_close($ch);
            //print_r($result);exit;

            $result_array = json_decode($result,true);
            if(isNull($result_array)){
            mail(env('APP_ADMIN_EMAIL'), "ScraperAPI Error", "content: " . $result );
            Log::info($result);
            return 0;
            }
            //echo "<pre>";
          //  print_r($result_array['organic_results']);exit;
            if(count($result_array['organic_results'])>0){
              foreach($result_array['organic_results'] as $data)
              {
                $pages = Page::where('seller_site_page_url', '=', $data['link'])->get();
                if(count($pages) > 0)
                {
                  Page::where('id', $pages[0]->id)
                 ->update([
                     'indexed' => 1,
                     'title' => $data['title']
                  ]);

                  $pageMetricHistory=PageMetricHistory::where('page_id',$pages[0]->id)->whereDate('updated_at',\Carbon\Carbon::today())->first();
                  if(isset($pageMetricHistory) && $pageMetricHistory->id>0){
                    $pageMetricHistory->indexed=1;
                    $pageMetricHistory->update();
                  }
                  else{
                    PageMetricHistory::create(
                    [
                      'page_id'=>$pages[0]->id,
                      'indexed'=>1,
                    ]);
                  }
                }
                GoogleIndexUrl::firstOrCreate([
                  'site_id' => $site['id'],
                  'page_url'=>$data['link'],
                ]);
              }
            }
         }

         return 0;
     }
}
