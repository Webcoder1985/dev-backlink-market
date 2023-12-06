<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $table = 'blmkt_categories';

  protected $fillable = [
      'title', 'level','parent_level'];

  public function children()
  {
    return $this->hasMany(Category::class, 'parent_level','level');
  }

}
