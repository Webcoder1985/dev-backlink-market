<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktSeoJobQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_job_queues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->unsigned();
            $table->string('seller_site_page_url');
            $table->bigInteger('batch_id')->unsigned();
            $table->dateTime('date_started', $precision = 0)->nullable();
            $table->dateTime('date_finished', $precision = 0)->nullable();
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
        Schema::dropIfExists('blmkt_job_queues');
    }
}
