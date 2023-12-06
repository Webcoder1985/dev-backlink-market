<?php

use FontLib\Table\Type\name;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlmktUserBalanceHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('blmkt_user_balance_history', function (Blueprint $table) {
          $table->tinyInteger("balance_account")->default("0")->after("amount")->comment("1:Buyer Balance, 2:Seller Balance");
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
