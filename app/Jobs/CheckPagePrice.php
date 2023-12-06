<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function PHPUnit\Framework\isNull;
use App\Models\Page;
use Illuminate\Support\Facades\Log;

class CheckPagePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    private $page = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     * Return Empty= job done
     * Return $this->fail() = job failed
     *  $this->release($delay);
     */
    public function handle()
    {

        $page = Page::where('id', $this->page->id)->first();
        //$moz_da, $moz_pa, $maj_tf, $maj_cf, $rd
        if (is_Null($page->moz_da) || is_Null($page->moz_pa) || is_Null($page->maj_tf) || is_Null($page->maj_cf) || is_Null($page->rd)) {
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
                   // mail(env('APP_ADMIN_EMAIL'), "Pageprice last chance after 4 failed Attempts", "Page ID: " . $this->page->id . "\n" . print_r($page, true));
                    break;
                default:
                    $delay = 40;
                    break;
            }
            log::info("CheckPagePrice Metrics not ready,re-add job with delay: " . $delay . " Attempt: " . $this->attempts() . " page.id:" . $this->page->id);
            $this->release($delay);
        } else {
            $price = getPriceByPage($this->page);
            $page->page_price_seller = $price['seller_price'];
            $page->page_price_buyer = $price['buyer_price'];
            $page->myrank = $price['myrank'];
            $page->update();
            log::info("getPriceByPage finished:" . $this->page->id);
        }
    }
}
