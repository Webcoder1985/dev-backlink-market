<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Interfaces\TicketReference;
use RexlManu\LaravelTickets\Traits\HasTicketReference;

class Order extends Model implements TicketReference
{
  use HasFactory;
  use HasTicketReference;

  protected $table = 'blmkt_orders';

  protected $fillable = [
      'pay_sn', 'user_id', 'user_full_name', 'user_email', 'order_amount', 'order_status', 'refund_status', 'refund_amount_buyer','refund_amount_seller','order_currency','paypro_subscription_id','paypro_order_id','ipn_log_paypro_id'];

  public function orderDetails(){
    return $this->hasMany(OrderDetail::class);
  }

  public function invoice(){
      return $this->belongsTo(PayproLog::class, 'ipn_log_paypro_id', 'id');
  }

  public function user(){
      return $this->belongsTo(User::class, 'user_id', 'id');
  }

  function hasReferenceAccess() : bool {
      return request()->user()->id == $this->buyer_id;
  }

  function toReference() : string {
    return "Order #(".$this->id.")";
  }
   public function is_active_order()
  {
    return $this->belongsTo(OrderDetail::class);
  }
}
