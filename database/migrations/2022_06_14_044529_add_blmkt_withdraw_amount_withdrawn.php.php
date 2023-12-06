<?php

use FontLib\Table\Type\name;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlmktOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_order_details', function (Blueprint $table) {
          $table->tinyInteger("refund_reason")->after("refund_status")->nullable()->default(null)->comment("1: Cancelled by buyer, 2: Cancelled by seller, 3: Cancelled by admin, 4: Cancelled website offline");
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
