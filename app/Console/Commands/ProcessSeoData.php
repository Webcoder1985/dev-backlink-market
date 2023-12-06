<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobQueue;
use App\Models\Batch;
use App\Models\Page;
use App\Models\PageMetricHistory;

class ProcessSeoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:processgoogledata';

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
        //Moz file
        if($batch->moz_file_id!=""){
          $url = $apiurl.'file_info.php?secret='.$api.'&id='.$batch->moz_file_id;
          $batch_data_string=file_get_contents($url);
          $file_data = explode('|',$batch_data_string); //parse data
          //print_r($file_data);
          //Moz data
          $processed_moz=false;
          if($file_data[3]=="finished"){
            $batch_data_output=file_get_contents($file_data[6]);
            $rows = explode(PHP_EOL,$batch_data_output); //parse data
            $s = array();
            $i=0;
            foreach($rows as $row) {
                $data = str_getcsv($row);
                //Skip header
                if($i>0){
                  $page=Page::where('seller_site_page_url','=',$data[0])->first();
                //  var_dump($page);
                  if(isset($page) && $page->id>0)
                  {
                    $page->moz_da=$data[1];
                    $page->moz_pa=$data[2];
                    $final_data[$page->id]['moz_da']=$data[1];
                    $final_data[$page->id]['moz_pa']=$data[2];
                    if($data[8]!="unknown"){$page->country=$data[8];}


                    $page->last_metric_update_time = \Carbon\Carbon::now();
                    $page->update();
                  }
                }
                $i++;
            }
            $processed_moz=true;
            //print_r($s);
          }

        }

        //Maj file
        if($batch->maj_file_id!=""){
          $url = $apiurl.'file_info.php?secret='.$api.'&id='.$batch->maj_file_id;
          $batch_data_string=file_get_contents($url);
          $file_data = explode('|',$batch_data_string); //parse data
          //print_r($file_data);exit;
          //Maj data
          $processed_maj=false;
          if($file_data[3]=="finished"){
            $batch_data_output=file_get_contents($file_data[6]);
            $rows = explode(PHP_EOL,$batch_data_output); //parse data
            $s = array();
            $i=0;
            foreach($rows as $row) {
                $data = str_getcsv($row);
                //print_r($data);
                //continue;
                //Skip header
                if($i>0){
                  $page=Page::where('seller_site_page_url','=',$data[0])->first();
                //  var_dump($page);
                  if(isset($page) && $page->id>0)
                  {
                      $page->maj_tf = $data[10];
                      $page->maj_cf = $data[11];
                      $page->maj_bl = $data[12];
                      $page->rd = $data[5];
                      $page->category = $data[13];
                      $ruled_passed = 0;
                      if ($page->maj_tf > 10 || $page->moz_da > 10) {
                          $ruled_passed = 1;
                      }
                      $page->ruled_passed = $ruled_passed;
                      $page->last_metric_update_time = \Carbon\Carbon::now();
                      if ($data[8] != "unknown"){ $page->country = $data[8];}
                      $page->update();


                      $final_data[$page->id]['maj_tf'] = $data[10];
                      $final_data[$page->id]['maj_cf'] = $data[11];
                      $final_data[$page->id]['maj_bl'] = $data[12];
                      $final_data[$page->id]['rd'] = $data[5];

                      //$deletedRows = JobQueue::where('page_id', $page->id)->delete();
                  }
                }
                $i++;
            }
            $processed_maj=true;
            //print_r($s);
          }

        }
        if($processed_moz && $processed_maj){
          //Uncomment to delete the successfully done job
          //Batch::where('id', $batch['id'])->delete();
          //$deletedRows = JobQueue::where('batch_id', $batch['id'])->delete();
          echo \Carbon\Carbon::today()."\n";
          foreach($final_data as $key => $data){
            $pageMetricHistory=PageMetricHistory::where('page_id',$key)->whereDate('updated_at',\Carbon\Carbon::today())->first();
            if(isset($pageMetricHistory) && $pageMetricHistory->id>0) {
                echo $key . "-found\n";
                $pageMetricHistory->moz_da = $data['moz_da'];
                $pageMetricHistory->moz_pa = $data['moz_pa'];
                $pageMetricHistory->maj_tf = $data['maj_tf'];
                $pageMetricHistory->maj_cf = $data['maj_cf'];
                $pageMetricHistory->maj_bl = $data['maj_bl'];
                $pageMetricHistory->rd = $data['rd'];
                $pageMetricHistory->update();
            }
            else{
              echo $key."-Created\n";
                PageMetricHistory::create(
                    [
                        'page_id' => $key,
                        'moz_da' => $data['moz_da'],
                        'moz_pa' => $data['moz_pa'],
                        'maj_tf' => $data['maj_tf'],
                        'maj_cf' => $data['maj_cf'],
                        'maj_bl' => $data['maj_bl'],
                        'rd' => $data['rd'],
                    ]);
            }
          }
        }
        else{
          Batch::where('id', $batch['id'])
         ->update([
             'date_started' => NULL
          ]);
        }

      }
        return 0;
    }
}
