<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  use HasFactory;

  protected $table = 'blmkt_notifications';

  protected $fillable = [
      'user_id', 'type'];
}
