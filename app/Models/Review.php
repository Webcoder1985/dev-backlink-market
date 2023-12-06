<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DGvai\Review\Reviewable;

class Review extends Model
{
    protected $table = 'model_reviews';

    use HasFactory;

    use Reviewable;

    protected $fillable = [
        'model_id',
        'model_type',
        'user_id',
        'review',
        'rating',
        'reply',
        'active'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
      }
}
