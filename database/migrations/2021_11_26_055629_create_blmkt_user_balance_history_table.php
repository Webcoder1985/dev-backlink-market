<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktUserBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_user_balance_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->tinyInteger('reference_type')->unsigned()->default(0)->comment('1 = subscription_payment, 2 = withdraw_aprroval,');
            $table->bigInteger('reference_id')->unsigned();
            $table->string('history_description')->nullable();
            $table->decimal('amount',10,2)->unsigned()->default(0.00);
            $table->tinyInteger('balance_type')->unsigned()->default(1)->comment('1(Debit):2(Credit)');
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
        Schema::dropIfExists('blmkt_user_balance_history');
    }
}
