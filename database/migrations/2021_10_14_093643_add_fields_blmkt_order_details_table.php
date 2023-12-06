<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsBlmktOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_order_details', function (Blueprint $table) {
            $table->tinyInteger('pause')->unsigned()->default(0)->comment('0 = Active 1= Inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blmkt_order_details', function (Blueprint $table) {
            //
        });
    }
}
