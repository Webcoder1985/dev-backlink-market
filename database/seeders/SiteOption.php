<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteOption extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('blmkt_options')->insert([
          'option_name' => 'site_name',
          'option_value' => 'Backlink Marketplace',
      ]);
    }
}
