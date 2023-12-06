<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    use HasFactory;

  protected $table = 'blmkt_transactions';

  protected $fillable = [
      'user_id', 'order_detail_id', 'amount', 'type', 'reference_id', 'payment_type', 'status', 'created_at','updated_at','payment_by'];
}
