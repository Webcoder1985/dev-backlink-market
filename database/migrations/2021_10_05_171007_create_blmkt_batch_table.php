<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_batch', function (Blueprint $table) {
            $table->id();
            $table->string('moz_file_id',50)->nullable();
            $table->string('maj_file_id',50)->nullable();
            $table->dateTime('date_started', $precision = 0)->nullable();
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
        Schema::dropIfExists('blmkt_batch');
    }
}
