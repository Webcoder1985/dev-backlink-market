<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleIndexUrl extends Model
{
    use HasFactory;
    protected $table = 'blmkt_google_index_url';

    protected $fillable = [
        'site_id', 'page_url'];
}
