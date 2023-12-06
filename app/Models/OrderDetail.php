<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
  use HasFactory;

  protected $table = 'blmkt_order_details';

  protected $fillable = [
      'order_id', 'seller_id', 'seller_full_name', 'seller_email', 'page_id', 'page_url', 'page_price_seller', 'page_price_buyer','promoted_url','link_content','anchor_text','no_follow','blmkt_links_id','buyer_id','refund_status','refund_reason','refund_amount_seller','refund_amount_buyer','seller_paid'];

  public function order(){
    return $this->belongsTo(Order::class);
  }
  public function page(){
    return $this->belongsTo(Page::class)->withTrashed();
  }

}
