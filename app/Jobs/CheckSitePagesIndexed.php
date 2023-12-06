<?php

namespace App\Jobs;

use Faker\Core\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SellerSite;
use Illuminate\Support\Facades\Log;

class CheckSitePagesIndexed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 120;
    private $site = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SellerSite $site)
    {
        $this->site=$site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        log::info("checkGoogleIndexDataCrawlbase: " . $this->site->id);
        file_put_contents("log_job.txt", "checkGoogleIndexDataCrawlbase: " . $this->site->id, FILE_APPEND);
        if (checkGoogleIndexDataCrawlbase($this->site) == 0) {
            log::info("checkGoogleIndexDataSerpsbot: " . $this->site->id);
            if (checkGoogleIndexDataSerpsbot($this->site) == 0) {
                log::info("checkGoogleIndexData: " . $this->site->id);
                if (checkGoogleIndexData($this->site) == 0) {
                    log::info("All Index checks failed: " . $this->site->id);
                    mail("info@backlink-market.com","All Index checks failed","SiteID:".$this->site->id);
                }

            }
        }
        //$this->fail($this->site);
    }
}
