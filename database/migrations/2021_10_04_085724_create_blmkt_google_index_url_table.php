<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktGoogleIndexUrlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_google_index_url', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->unsigned();
            $table->string('page_url');
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
        Schema::dropIfExists('blmkt_google_index_url');
    }
}
