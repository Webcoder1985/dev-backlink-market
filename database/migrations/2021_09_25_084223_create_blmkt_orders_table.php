<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_orders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('pay_sn')->unsigned();
            $table->bigInteger('buyer_id')->unsigned();
            $table->string('buyer_full_name');
            $table->string('buyer_email');
            $table->decimal('order_amount',10,2)->unsigned()->default(0.00);
            $table->tinyInteger('order_status')->unsigned()->default(10)->comment('0(canceled)10(default):pending;20:paid');
            $table->tinyInteger('refund_status')->unsigned()->default(0)->comment('0 no refund,1: subrefund,2: total refund');
            $table->decimal('refund_amount',10,2)->unsigned()->default(0.00)->comment('refund amount');
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
        Schema::dropIfExists('blmkt_orders');
    }
}
