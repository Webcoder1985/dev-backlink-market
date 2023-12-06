<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlmktOptionsPluginVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert some stuff
        DB::table('blmkt_options')->insert(
            array(
                'option_name' => 'plugin_version',
                'option_value' => '1.0.0',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
