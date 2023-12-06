<?php

use FontLib\Table\Type\name;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlmktOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_orders', function (Blueprint $table) {
          $table->decimal("refund_amount_seller")->default("0.00")->after("refund_amount");
          $table->renameColumn("refund_amount","refund_amount_buyer");
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
