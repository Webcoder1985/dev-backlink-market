<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessBulkPageMyAddrAPI;
use App\Models\Page;
use App\Models\Batch;
use Storage;

class BulkAddPageMyAddrAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:bulkaddpagemyaddrapi';

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
      $offset=0;
      $limit=100;
      $pages = Page::where('is_active', '=', 1)->where('indexed', '=', 1)->orWhere('last_metric_update_time','<=', $date)->orWhere('last_metric_update_time','=', '')->offset($offset)->limit($limit)->get();
      while(!$pages->isEmpty()){

        $batch=Batch::create([
          'file_id'=>''
        ]);
        $url_collection="";
        foreach($pages as $page)
        {
          $url_collection.=$page['seller_site_page_url']."\n";
        }

        if($url_collection!=""){
          Storage::put($batch->id."_url.txt", $url_collection);

          $file_ids=add_seo_rank_url_file_upload($batch->id."_url.txt");
          if(isset($file_ids) && $file_ids!=""){
            $batch->moz_file_id=$file_ids['moz_file_id'];
            $batch->maj_file_id=$file_ids['maj_file_id'];
            $batch->update();
            ProcessBulkPageMyAddrAPI::dispatch($batch)->onQueue('Digimetr')->delay(now()->addMinutes(1));;
          }
        }

        $offset+=$limit;
        $pages = Page::where('is_active', '=', 1)->where('indexed', '=', 1)->orWhere('last_metric_update_time','<=', $date)->orWhere('last_metric_update_time','=', '')->offset($offset)->limit($limit)->get();
      }


      return 0;
    }
}
