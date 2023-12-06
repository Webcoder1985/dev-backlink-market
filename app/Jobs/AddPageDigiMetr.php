<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class AddPageDigiMetr implements ShouldQueue
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
        Log::info("add_digimetr_url_query by AddPageDigiMetr.php: " . $this->page->id);
        add_digimetr_url_quey($this->page->seller_site_page_url);
        Log::info("ProcessPageDigimetrJob added: " . $this->page->id);
        ProcessPageDigimetrJob::dispatch($this->page)->onQueue('Digimetr')->delay(now()->addSeconds(45));
    }
}
