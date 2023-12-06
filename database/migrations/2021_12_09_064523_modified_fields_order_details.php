<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedFieldsOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_order_details', function (Blueprint $table) {
            $table->renameColumn('page_price', 'page_price_seller');
            $table->renameColumn('page_pay_price', 'page_price_buyer');
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
