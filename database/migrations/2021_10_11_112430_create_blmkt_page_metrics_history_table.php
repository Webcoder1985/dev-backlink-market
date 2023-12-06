<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktPageMetricsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_page_metric_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('page_id');
            $table->boolean('indexed')->default(0);
            $table->smallInteger('moz_da')->default(0);
            $table->smallInteger('moz_pa')->default(0);
            $table->smallInteger('maj_tf')->default(0);
            $table->smallInteger('maj_cf')->default(0);
            $table->smallInteger('maj_bl')->default(0);
            $table->smallInteger('rd')->default(0);
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
        Schema::dropIfExists('blmkt_page_metric_histories');
    }
}
