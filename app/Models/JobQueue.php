<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQueue extends Model
{
    use HasFactory;

    protected $table = 'blmkt_job_queues';

    protected $fillable = [
        'page_id','batch_id','seller_site_page_url', 'date_started', 'date_finished'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
