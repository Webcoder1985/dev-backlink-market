<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status');
            $table->bigInteger('buyer_id');
            $table->bigInteger('last_order_id')->unsigned()->nullable(); 
            $table->bigInteger('seller_id')->unsigned(); 
            $table->string('seller_full_name'); 
            $table->string('seller_email'); 
            $table->bigInteger('page_id')->unsigned(); 
            $table->string('page_url'); 
            $table->decimal('page_price_buyer')->default('0.00');
            $table->decimal('page_price_seller')->default('0.00');
            $table->string('promoted_url'); 
            $table->string('content_before_anchor_text')->nullable();
            $table->string('anchor_text')->nullable();
            $table->string('content_after_anchor_text')->nullable();
            $table->tinyInteger('no_follow')->unsigned()->default(0)->comment('0 = No 1= Yes');
            $table->Integer('offline_counter')->nullable();
            $table->string('last_http_status_code')->nullable();
            $table->dateTime('last_link_live_check')->nullable();
            $table->date('buyer_paid_until')->nullable();
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
        Schema::dropIfExists('blmkt_links');
    }
}
