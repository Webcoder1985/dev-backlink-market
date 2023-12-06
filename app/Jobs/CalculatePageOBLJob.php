<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Page;
use Illuminate\Support\Facades\Log;

class CalculatePageOBLJob implements ShouldQueue
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
      $components = parse_url($this->page->seller_site_page_url);
      $host = $components['host'];
      try {
        if($this->page->obl<=0)
        {
          //echo "Finding external";
            Log::info("Check OBL: " . $this->page->id);
            $site_html = get_html_from_url_quey($this->page->seller_site_page_url);
          if($site_html != '')
          {
            $extrenal_url = get_ext_count_from_html_quey($site_html,$host);
              Page::where('id', $this->page->id)
                  ->update([
                      'obl' => $extrenal_url
                  ]);
              Log::info("OBL Updated with " . $extrenal_url . " : " . $this->page->id);
          }
        }
      } catch (Throwable $e) {
          Log::info("OBL Updater Error " . $e->getMessage() . " : " . $this->page->id);

      }
    }
}
