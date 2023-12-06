<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('seller_id')->unsigned();
            $table->string('seller_full_name');
            $table->string('seller_email');
            $table->bigInteger('page_id')->unsigned();
            $table->string('page_url');
            $table->decimal('page_price',10,2)->unsigned()->default(0.00);
            $table->decimal('page_pay_price',10,2)->unsigned()->default(0.00);
            $table->string('promoted_url');
            $table->string('content_before_anchor_text')->nullable();
            $table->string('anchor_text');
            $table->string('content_after_anchor_text')->nullable();
            $table->tinyInteger('no_follow')->unsigned()->default(0)->comment('0 = No 1= Yes');
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
        Schema::dropIfExists('blmkt_order_details');
    }
}
