<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addtypecolumntoblmktpages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_pages', function (Blueprint $table) {
            $table->string('type', 10)->after('content')->default(0);
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
