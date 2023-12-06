<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PageMetricHistory;
use Illuminate\Support\Facades\Log;

class FindPageSEORankMyAddr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $page = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page)
    {
      $this->page=$page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CheckPagePrice::dispatch($this->page)->onQueue('PagePrice')->delay(now()->addSeconds(60));
        Log::info("CheckPagePrice job added: " . $this->page->id);
        $api = env('SEORANKAPI');
        $apiurl = env('SEORANKURL');

        $ch = curl_init();
        $url = $apiurl . 'api2/moz+alexa/' . $api . '/' . $this->page->seller_site_page_url;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_data = curl_exec($ch); //you need to save this FILE_ID for get file status and download reports in future
      curl_close($ch);
      //print_r($response_data);
      $seo_data=json_decode($response_data,true);
      $this->page->moz_da=$seo_data['da'];
      $this->page->moz_pa=$seo_data['pa'];
        $this->page->update();
        Log::info("FindPageSEORankMyAddr Moz updated: " . $this->page->id);

      $pageMetricHistory=PageMetricHistory::where('page_id',$this->page->id)->whereDate('updated_at',\Carbon\Carbon::today())->first();
      if(isset($pageMetricHistory) && $pageMetricHistory->id>0) {
          echo $this->page->id . "-found\n";
          $pageMetricHistory->moz_da = $seo_data['da'];
          $pageMetricHistory->moz_pa = $seo_data['pa'];
          $pageMetricHistory->update();
      }
      else{
          PageMetricHistory::create(
              [
                  'page_id' => $this->page->id,
                  'moz_da' => $seo_data['da'],
                  'moz_pa' => $seo_data['pa'],
              ]);
      }
    }
}
