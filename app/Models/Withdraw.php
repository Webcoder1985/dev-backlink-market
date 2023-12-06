<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;
    protected $table = 'blmkt_withdraw';
    protected $dates = ['payout_at'];
    protected $fillable = [
        'user_id', 'amount', 'status', 'created_at', 'updated_at', 'paypal_email', 'payout_at', 'amount_withdrawn'];

      public function user(){
        return $this->belongsTo(User::class,'user_id');
      }
}
