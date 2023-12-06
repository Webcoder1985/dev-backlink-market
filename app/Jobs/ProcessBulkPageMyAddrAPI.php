<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Batch;
use App\Models\Page;
use App\Models\PageMetricHistory;
use Illuminate\Support\Facades\Log;
class ProcessBulkPageMyAddrAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $batch = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
     public function __construct($batch)
     {
       $this->batch=$batch;
     }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = env('SEORANKAPI');
        $apiurl = env('SEORANKURL');

        //Moz file
        Log::info("ProcessBulkPageMyAddrAPI BatchID: " . $this->batch->id);

        Batch::where('id', $this->batch->id)
            ->update([
                'date_started' => date('Y-m-d H:i:s')
            ]);
        if ($this->batch->moz_file_id != "") {
            $url = $apiurl . 'file_info.php?secret=' . $api . '&id=' . $this->batch->moz_file_id;
            //echo $url;
            $batch_data_string = file_get_contents($url);
            $file_data = explode('|', $batch_data_string); //parse data
          //print_r($file_data);
          //Moz data
          $processed_moz = false;
          if ($file_data[3] == "finished") {
              $batch_data_output = file_get_contents($file_data[6]);
              $rows = explode(PHP_EOL, $batch_data_output); //parse data
              $s = array();
              $i = 0;
              foreach ($rows as $row) {
                  $data = str_getcsv($row);
                  //Skip header
                  if ($i > 0) {
                      $pages = Page::where('seller_site_page_url', '=', $data[0])->get();
                      //  var_dump($page);
                      if (isset($pages) && $pages->count() > 0) {
                          foreach ($pages as $page) {
                              $page->moz_da = $data[1];
                              $page->moz_pa = $data[2];
                              $final_data[$page->id]['moz_da'] = $data[1];
                              $final_data[$page->id]['moz_pa'] = $data[2];
                          }
                          $page->last_metric_update_time = \Carbon\Carbon::now();
                          $page->update();
                          Log::info("CheckPagePrice job added by BulkPageMyAddr: " . $page->id);
                          CheckPagePrice::dispatch($page)->onQueue('PagePrice')->delay(now()->addSeconds(60));


                      }
              }
              $i++;
          }
          $processed_moz=true;
          //print_r($s);
        }
        else {
            Batch::where('id', $this->batch->id)
                ->update([
                    'date_started' => NULL
                ]);
            Log::info("ProcessBulkPageMyAddrAPI Re-added Batch: " . $this->batch->id);

            ProcessBulkPageMyAddrAPI::dispatch($this->batch)->onQueue('MyAddrAPI')->delay(now()->addSeconds(env('MYADDR_PROCESS_RESTART_AFTER')));  //added to back in queue and try to execute after 30 seconds
            return;
        }
      }


      if($processed_moz==true) {
          //Uncomment to delete the successfully done job
          //Batch::where('id', $batch['id'])->delete();
          //$deletedRows = JobQueue::where('batch_id', $batch['id'])->delete();
          //echo \Carbon\Carbon::today()."\n";

          foreach ($final_data as $key => $data) {
              $pageMetricHistory = PageMetricHistory::where('page_id', $key)->whereDate('updated_at', \Carbon\Carbon::today())->first();
              if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                  //echo $key . "-found\n";
                  $pageMetricHistory->moz_da = $data['moz_da'];
                  $pageMetricHistory->moz_pa = $data['moz_pa'];
                  $pageMetricHistory->update();
              } else {
                  //echo $key."-Created\n";
              PageMetricHistory::create(
                  [
                      'page_id' => $key,
                      'moz_da' => $data['moz_da'],
                      'moz_pa' => $data['moz_pa'],
                  ]);
          }
        }
      }

      else{
          Batch::where('id', $this->batch->id)
              ->update([
                  'date_started' => NULL
              ]);
          Log::info("ProcessBulkPageMyAddrAPI Re-added Batch: " . $this->batch->id);
          ProcessBulkPageMyAddrAPI::dispatch($this->batch)->onQueue('MyAddrAPI')->delay(now()->addMinutes(2));  //added to back in queue and try to execute after 30 seconds
        return;
      }

    }
}
