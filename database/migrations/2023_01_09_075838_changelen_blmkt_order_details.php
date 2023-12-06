<?php

use FontLib\Table\Type\name;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangelenBlmktOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_order_details', function (Blueprint $table) {
            $table->string("link_content",355)->change();
      });
      Schema::table('blmkt_links', function (Blueprint $table) {
            $table->string("link_content",355)->change();
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
