<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedFieldsPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_pages', function (Blueprint $table) {
            $table->renameColumn('price_user', 'page_price_seller');
            $table->renameColumn('price_display', 'page_price_buyer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blmkt_pages', function (Blueprint $table) {
            //
        });
    }
}
