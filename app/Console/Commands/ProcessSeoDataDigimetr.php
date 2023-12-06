<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobQueue;
use App\Models\Batch;
use App\Models\Page;
use App\Models\PageMetricHistory;

class ProcessSeoDataDigimetr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:processseodatadigimetr';

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
      $batches = Batch::whereNull('date_started')->get();
      $final_data=array();
      foreach($batches as $batch)
      {
        Batch::where('id', $batch['id'])
       ->update([
           'date_started' => date('Y-m-d H:i:s')
        ]);

        $jobQueues=JobQueue::where('batch_id','=',$batch['id'])->get();
        echo $batch['id'];
        foreach($jobQueues as $jobQueue){
          $response=add_digimetr_url_quey($jobQueue->seller_site_page_url);
          if($response){
            //print_r($response);
            $page=Page::where('id','=',$jobQueue->page_id)->first();
            if(isset($page) && $page->id>0)
            {
              $page->maj_tf=$response->tf;
              $page->maj_cf=$response->cf;
              $page->last_metric_update_time = \Carbon\Carbon::now();
              $page->update();

              $pageMetricHistory=PageMetricHistory::where('page_id',$page->id)->whereDate('updated_at',\Carbon\Carbon::today())->first();
              if(isset($pageMetricHistory) && $pageMetricHistory->id>0){
                echo $page->id."-found\n";
                $pageMetricHistory->maj_tf=$page->maj_tf;
                $pageMetricHistory->maj_cf= $page->maj_cf;
                $pageMetricHistory->update();
              }
              else{
                echo $page->id."-Created\n";
                PageMetricHistory::create(
                [
                  'page_id'=>$page->id,
                  'maj_tf'=>$page->maj_tf,
                  'maj_cf'=>$page->maj_cf,
                ]);
              }
            }
          }
        }

        exit;

      }
        return 0;
    }
}
