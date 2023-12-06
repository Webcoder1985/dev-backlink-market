<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedFieldsBlmktOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_orders', function (Blueprint $table) {
            $table->renameColumn('buyer_id', 'user_id');
            $table->renameColumn('buyer_full_name', 'firstname');
            $table->renameColumn('buyer_email', 'user_email');
            $table->string('paypro_subscription_id')->nullable()->after('refund_amount');
            $table->string('paypro_order_id')->nullable()->after('paypro_subscription_id');
            $table->string('ipn_log_paypro_id')->nullable()->after('paypro_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blmkt_orders', function (Blueprint $table) {
            //
        });
    }
}
