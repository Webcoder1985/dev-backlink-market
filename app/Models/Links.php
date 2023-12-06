<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Page;
use App\Models\Site;

class Links extends Model
{
    use HasFactory;

    protected $table = 'blmkt_links';

    protected $fillable = [
        'status', 'buyer_id', 'last_order_id', 'seller_id', 'seller_full_name', 'seller_email', 'page_id', 'page_url', 'page_price_buyer', 'page_price_seller', 'promoted_url', 'link_content', 'anchor_text', 'no_follow', 'offline_counter', 'last_http_status_code', 'last_link_live_check', 'buyer_paid_until','updated_at'];

    public function page()
    {
        return $this->belongsTo(Page::class)->withTrashed();
    }

    public function page_by_id()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id')->withTrashed();
    }
}
