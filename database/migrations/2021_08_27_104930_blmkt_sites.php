<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlmktSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_sites', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('seller_id')->default(0);
            $table->string('site_url');
            $table->string('site_auth_key');
            $table->tinyInteger('is_active')->default(0);
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
