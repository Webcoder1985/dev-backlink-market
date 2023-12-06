<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'blmkt_batch';

    protected $fillable = [
        'moz_file_id','maj_file_id','date_started'];

    public function page()
    {
        return $this->hasMany(JobQueue::class);
    }
}
