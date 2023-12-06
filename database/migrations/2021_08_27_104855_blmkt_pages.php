<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlmktPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Schema::dropIfExists('blmkt_pages');
        Schema::create('blmkt_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('seller_id');
            $table->integer('seller_site_id')->default(0);
            $table->string('seller_site_url', 400); // ->unique() needs to be unique on production to avoid duplicate URLs in Marketplace
            $table->bigInteger('seller_site_page_id')->default(0);
            $table->string('seller_site_page_url');
            $table->boolean('indexed')->default(0);
            $table->smallInteger('moz_da')->default(0);
            $table->smallInteger('moz_pa')->default(0);
            $table->smallInteger('maj_tf')->default(0);
            $table->smallInteger('maj_cf')->default(0);
            $table->smallInteger('maj_bl')->default(0);
            $table->smallInteger('rd')->default(0);
            $table->smallInteger('obl')->default(0);
            $table->string('language',2)->default(0);
            $table->string('country',2)->default(0);
            $table->string('tld',50)->default(0);
            $table->string('category')->default(0);
            $table->string('title', 400)->default(0);
            $table->decimal('price_user')->default('0.00');
            $table->decimal('price_display')->default('0.00');
            $table->dateTime('last_metric_update_time')->nullable()->default(null);
            $table->integer('order_count_active')->default(0);
            $table->integer('order_count_total')->default(0);
            $table->boolean('is_active')->default(0);

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
        //
    }
}
