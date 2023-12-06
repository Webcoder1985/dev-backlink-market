<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Page;
use App\Models\PageMetricHistory;
use Illuminate\Support\Facades\Log;

class ProcessPageDigimetrJob implements ShouldQueue
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
        Log::info("add_digimetr_url_query by ProcessPageDigimetrJob.php: " . $this->page->id);
        $response = add_digimetr_url_quey($this->page->seller_site_page_url);
      if($response){
        $page=Page::where('id','=',$this->page->id)->first();
        if(isset($page) && $page->id>0)
        {
            $page->maj_tf = $response->tf;
            $page->maj_cf = $response->cf;
            $page->maj_bl = $response->el;
            $page->rd = $response->refd;
            $page->last_metric_update_time = \Carbon\Carbon::now();
            $page->update();
            Log::info("Maj updated by ProcessPageDigimetrJob.php: " . $this->page->id);
            $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
            if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                echo $page->id . "-found\n";
                $pageMetricHistory->maj_tf = $page->maj_tf;
                $pageMetricHistory->maj_cf = $page->maj_cf;
                $pageMetricHistory->maj_bl = $page->maj_bl;
                $pageMetricHistory->rd = $page->rd;
                $pageMetricHistory->update();
            }
          else{
            echo $page->id."-Created\n";
              PageMetricHistory::create(
                  [
                      'page_id' => $page->id,
                      'maj_tf' => $page->maj_tf,
                      'maj_cf' => $page->maj_cf,
                      'maj_bl' => $page->maj_bl,
                      'rd' => $page->rd,
                  ]);
          }
        } else {
            Log::info("page or page id not set  ProcessPageDigimetrJob.php: " . $this->page->id);
        }
      } else {
          Log::info("Maj not updated Response empty by ProcessPageDigimetrJob.php: " . $this->page->id);
          switch ($this->attempts()) {
                case 1:
                    $delay = 30;
                    break;
                case 2:
                    $delay = 60;
                    break;
                case 3:
                    $delay = 120;
                    break;
                case 4:
                    $delay = 180;
                    break;
                case 5:
                    $delay = 300;
                    mail(env('APP_ADMIN_EMAIL'), "ProcessPageDigimetrJob last chance after 4 failed Attempts", "Page ID: " . $this->page->id . "\n" . print_r($this->page, true));
                    break;
                default:
                    $delay = 40;
                    break;
            }
            log::info("ProcessPageDigimetrJob Digimetr Maj. Metric not ready,re-add job with delay: " . $delay . " Attempt: " . $this->attempts() . " page.id:" . $this->page->id);
            $this->release($delay);
      }
    }
}
