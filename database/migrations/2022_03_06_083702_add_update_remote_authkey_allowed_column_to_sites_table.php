<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateRemoteAuthkeyAllowedColumnToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blmkt_sites', function (Blueprint $table) {
            $table->boolean('update_remote_authkey_allowed')->default(false)->after('is_ban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blmkt_sites', function (Blueprint $table) {
            //
        });
    }
}
