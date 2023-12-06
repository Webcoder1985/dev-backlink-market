<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\PageCategory;
use Illuminate\Support\Facades\Log;

class FindPageCategories implements ShouldQueue
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
        Log::info("get_categories_using_content id: " . $this->page->id);
        $categories = get_categories_using_content($this->page->content);
        if (count($categories) > 0) {
            DB::table('blmkt_page_category')->where('page_id', $this->page->id)->delete();
            foreach ($categories as $category) {
                $page_categoory = PageCategory::create([
                    'page_id' => $this->page->id,
                    'category' => $category,
                ]);
            }
        } else {
            mail(env('APP_ADMIN_EMAIL'), "No category found id:" . $this->page->id, "id: " . $this->page->id . "\nURL: " . $this->page->seller_site_page_url . "\ntitle: " . $this->page->title . "\nContent: " . $this->page->content);
            Log::info("Category Check Failed id: " . $this->page->id);
        }
        Log::info("get_language_using_content id: " . $this->page->id);
        $language = get_language_using_content($this->page->content);
        $this->page->language=$language;
        $this->page->update();


    }
}
