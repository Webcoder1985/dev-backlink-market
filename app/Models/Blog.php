<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'url',
        'language',
        'image',
        'pa',
        'cf',
        'tld',
        'da',
        'dr',
        'rd',
        'obl',
        'seo_metric',
        'traffic',
        'price',
    ];
}
