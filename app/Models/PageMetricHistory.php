<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobQueue;
use App\Models\GoogleIndexUrl;

class PageMetricHistory extends Model
{
    use HasFactory;

    protected $table = 'blmkt_page_metric_histories';

    protected $fillable = [
        'page_id', 'indexed', 'moz_da', 'moz_pa', 'maj_tf', 'maj_cf', 'maj_bl', 'rd'
    ];

}
