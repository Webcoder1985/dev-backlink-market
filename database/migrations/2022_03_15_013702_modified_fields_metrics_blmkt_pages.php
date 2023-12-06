<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class modifiedfieldsmetricsblmktpages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_pages', function (Blueprint $table) {
            $table->smallInteger('moz_da')->nullable()->change()->default(null);
            $table->smallInteger('moz_pa')->nullable()->change()->default(null);
            $table->smallInteger('maj_tf')->nullable()->change()->default(null);
            $table->smallInteger('maj_cf')->nullable()->change()->default(null);
            $table->integer('maj_bl')->nullable()->change()->default(null);
            $table->smallInteger('rd')->nullable()->change()->default(null);
            $table->boolean('indexed')->nullable()->change()->default(null);
            $table->smallInteger('obl')->nullable()->change()->default(null);
            $table->string('language', 50)->nullable()->change()->default(null);
            $table->string('country', 2)->nullable()->change()->default(null);
            $table->string('tld', 50)->nullable()->change()->default(null);
            $table->string('category')->nullable()->change()->default(null);
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
