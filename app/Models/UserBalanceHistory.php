<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalanceHistory extends Model
{
    use HasFactory;
    protected $table = 'blmkt_user_balance_history';

  protected $fillable = [
      'user_id', 'reference_type', 'amount', 'history_description', 'order_id', 'balance_type', 'created_at','updated_at'];
}
