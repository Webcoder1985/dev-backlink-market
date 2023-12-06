<?php
namespace App\Console\Commands;

namespace App\Http\Controllers;

use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\Batch;
use App\Models\JobQueue;
use Storage;

use Illuminate\Http\Request;

use App\Models\SellerSite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PayproLog;
use App\Models\Links;
use Illuminate\Support\Facades\Log;

class FetchSeoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:fetchseodata';

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
      $api = env('SEORANKAPI');
      $apiurl = env('SEORANKURL');
      //$this->info("TEST");
      $date = \Carbon\Carbon::now()->subDays(7);
      //echo $date."\n";
      $pages = Page::where('is_active', '=', 1)->where('indexed', '=', 1)->orWhere('last_metric_update_time','<=', $date)->orWhere('last_metric_update_time','=', '')->get();
      //$this->info(count($pages));exit;

      $batch=Batch::create([
        'file_id'=>''
      ]);
      //echo $batch->id; exit;
      $url_collection="";
      foreach($pages as $page)
      {
        //echo $page->id."\n"; continue;
        $url_collection.=$page['seller_site_page_url']."\n";
        add_seo_rank_url_quey($page['seller_site_page_url']);
        $components = parse_url($page['seller_site_url']);
        $host = $components['host'];
        try {
          if($page->obl<=0)
          {
            //echo "Finding external";
            $site_html = get_html_from_url_quey($page['seller_site_page_url']);
            if($site_html != '')
            {
              $extrenal_url = get_ext_count_from_html_quey($site_html,$host);
              Page::where('id', $page['id'])
               ->update([
                   'obl' => $extrenal_url
                ]);
            }
          }
        } catch (Throwable $e) {


        }
          /*$site_url = $page['seller_site_page_url'];
          $ch = curl_init();
          $url = $apiurl.'api3/'.$api.'/'.$site_url;
          $curlConfig = array(
               CURLOPT_URL            => $url,
               CURLOPT_POST           => true,
               CURLOPT_RETURNTRANSFER => true
           );
           curl_setopt_array($ch, $curlConfig);
           $result = curl_exec($ch);
           curl_close($ch);

          $ch = curl_init();
          $url = $apiurl.'api2/moz+alexa+sr+fb/'.$api.'/'.$site_url;
          $curlConfig = array(
               CURLOPT_URL            => $url,
               CURLOPT_POST           => true,
               CURLOPT_RETURNTRANSFER => true
           );
           curl_setopt_array($ch, $curlConfig);
           $result = curl_exec($ch);
           curl_close($ch);*/


          //$this->info($page['id']);
          //echo "Page URL:".$page['seller_site_page_url'];
          JobQueue::updateOrCreate([
            'page_id'=>$page['id'],
            'seller_site_page_url'=>$page['seller_site_page_url']

          ],[
            'batch_id'=>$batch->id
          ]);
      }
      //exit;
      if($url_collection!=""){
        Storage::put($batch->id."_url.txt", $url_collection);

        $file_ids=add_seo_rank_url_file_upload($batch->id."_url.txt");
        if(isset($file_ids) && $file_ids!=""){
          $batch->moz_file_id=$file_ids['moz_file_id'];
          $batch->maj_file_id=$file_ids['maj_file_id'];
          $batch->update();
        }
      }

      return 0;
    }
}
