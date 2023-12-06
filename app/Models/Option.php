<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DGvai\Review\Reviewable;

class Option extends Model
{
    protected $table = 'blmkt_options';

    use HasFactory;

    use Reviewable;

    protected $fillable = [
        'option_name',
        'option_value',
    ];
}
