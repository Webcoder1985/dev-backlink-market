<?php

use FontLib\Table\Type\name;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlmktOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_order_details', function (Blueprint $table) {
         $table->dropColumn("content_after_anchor_text");
         $table->renameColumn("content_before_anchor_text","link_content");
      });
      Schema::table('blmkt_links', function (Blueprint $table) {
         $table->dropColumn("content_after_anchor_text");
         $table->renameColumn("content_before_anchor_text","link_content");
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
