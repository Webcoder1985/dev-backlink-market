<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
  use HasFactory;

  protected $table = 'blmkt_page_category';

  protected $fillable = [
      'page_id', 'category'];

  public function category_title()
  {
    return $this->belongsTo(Category::class, 'category','level');
  }

}
