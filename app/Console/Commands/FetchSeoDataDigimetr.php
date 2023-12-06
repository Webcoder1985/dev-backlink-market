<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\Batch;
use App\Models\JobQueue;
use Storage;

class FetchSeoDataDigimetr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:fetchseodatadigimetr';

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
        add_digimetr_url_quey($page['seller_site_page_url']);
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
        JobQueue::updateOrCreate([
          'page_id'=>$page['id'],
          'seller_site_page_url'=>$page['seller_site_page_url']

        ],[
          'batch_id'=>$batch->id
        ]);
      }


      return 0;
    }
}
