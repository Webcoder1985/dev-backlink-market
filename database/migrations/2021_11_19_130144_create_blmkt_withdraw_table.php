<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlmktWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_withdraw', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->decimal('amount',10,2)->unsigned()->default(0.00);
            $table->tinyInteger('status')->unsigned()->default(0)->comment('0 Pending,1: Approved,2:Decline');
            $table->string('reference_id')->nullable();
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
        Schema::dropIfExists('blmkt_withdraw');
    }
}
