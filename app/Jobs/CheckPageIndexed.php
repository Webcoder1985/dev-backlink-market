<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPageIndexed implements ShouldQueue
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
        log::info("checkGoogleIndexPageSerpsbot: " . $this->page->id);
        if (checkGoogleIndexPageSerpsbot($this->page) == 0) {
            log::info("checkGoogleIndexPageCrawlbase: " . $this->page->id);
            if (checkGoogleIndexPageCrawlbase($this->page) == 0) {
                log::info("checkGoogleIndexPage: " . $this->page->id);
                if (checkGoogleIndexPage($this->page) == 0) {
                    log::info("All failed: " . $this->page->id);
                }

            }
        }
    }
}
