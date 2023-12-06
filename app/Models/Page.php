<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobQueue;
use App\Models\SellerSite;
use App\Models\GoogleIndexUrl;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\FindPageCategories;
use function PHPUnit\Framework\isNull;

class Page extends Model
{
    use HasFactory;

    protected $table = 'blmkt_pages';

    protected $fillable = [
        'seller_id', 'seller_site_id', 'seller_site_url', 'seller_site_page_id', 'seller_site_page_url', 'indexed', 'moz_da', 'moz_pa', 'maj_tf', 'maj_cf', 'maj_bl', 'rd', 'obl', 'language', 'country', 'tld', 'category', 'type', 'publish_date', 'title', 'myrank', 'page_price_seller', 'page_price_buyer', 'last_metric_update_time', 'order_count_active', 'order_count_total', 'is_active', 'is_ban', 'content', 'tags'
    ];

    public function site()
    {
        return $this->belongsTo(SellerSite::class, 'seller_site_id')->withTrashed();
    }

    public function save(array $options = [])
    {
        parent::save($options);
    }
    /*
   if(isset($this->id) && $this->id>0 )
     $isCreated=parent::save($options);
   else{
     $isCreated=parent::save($options);
     if($isCreated){
       FindPageCategories::dispatch($this);
       /*$googledata = GoogleIndexUrl::where('page_url', '=', $this->seller_site_page_url)->get();
       if(count($googledata) > 0)
       {
         Page::where('id', $this->id)
        ->update([
            'indexed' => 1
         ]);
       }
       add_seo_rank_url_quey($this->seller_site_page_url);
       JobQueue::firstOrCreate([
         'page_id'=>$this->id,
       ]);*/

    /*}
  }*/


}
