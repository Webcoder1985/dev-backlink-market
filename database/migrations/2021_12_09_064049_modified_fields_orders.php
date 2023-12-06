<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedFieldsOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_orders', function (Blueprint $table) {
            //
            $table->renameColumn('firstname', 'user_full_name');
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
