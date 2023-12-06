<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNullableFieldsBlmktPayproLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_paypro_logs', function (Blueprint $table) {
        $table->string('order_placed_time_utc')->nullable()->change();
        $table->string('order_placed_time_customer_timezone')->nullable()->change();
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
