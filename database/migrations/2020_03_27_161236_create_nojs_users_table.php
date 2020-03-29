<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNojsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nojs_users', function (Blueprint $table) {
            $table->string('nojs', 8)->primary();
            $table->string('site', 20)->nullable()->default(null);
            $table->string('provinsi', 20)->nullable()->default(null);
            $table->string('lc', 10)->nullable()->default(null);
            $table->string('mitra', 10)->nullable()->default(null);
            $table->string('ip', 15)->nullable()->default(null);
            $table->string('latitude', 20)->nullable()->default(null);
            $table->string('longitude', 20)->nullable()->default(null);
            $table->integer('id_lvdvsat')->nullable()->default(null);
            $table->integer('id_ping')->nullable()->default(null);
            $table->integer('id_batvolt')->nullable()->default(null);
            $table->integer('id_vsatcurr')->nullable()->default(null);
            $table->integer('id_btscurr')->nullable()->default(null);
            $table->integer('no_urut')->nullable()->default(null);
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
        Schema::dropIfExists('nojs_users');
    }
}