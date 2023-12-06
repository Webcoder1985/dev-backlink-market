<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('balance')->default('0.00');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('user_status')->default(0)->comment("0=registered not email verified, 1=registered email verified, 2=banned, 3=admin");
            $table->ipAddress('registration_ip');
            $table->ipAddress('last_login_ip');
            $table->string('business_name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('street')->nullable();
            $table->string('street_number')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('vat')->nullable();
            $table->string('notes')->nullable();
            $table->string('terms')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
