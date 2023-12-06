<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedFieldsBlmktOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_order_details', function (Blueprint $table) {
            $table->bigInteger('blmkt_links_id')->nullable()->after('order_id');
            $table->bigInteger('buyer_id')->nullable()->after('blmkt_links_id');
            $table->Integer('refund_status')->nullable()->after('no_follow');
            $table->decimal('refund_amount')->default('0.00')->after('refund_status');
            $table->tinyInteger('seller_paid')->nullable()->after('refund_amount');
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
