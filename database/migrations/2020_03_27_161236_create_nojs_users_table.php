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
            $table->string('site', 20);
            $table->string('provinsi', 20);
            $table->string('lc', 10);
            $table->string('mitra', 10);
            $table->string('ip', 15);
            $table->string('latitude', 20);
            $table->string('longitude', 20);
            $table->smallInteger('id_lvdvsat');
            $table->smallInteger('id_ping');
            $table->smallInteger('id_batvolt');
            $table->smallInteger('id_vsatcurr');
            $table->smallInteger('id_btscurr');
            $table->smallInteger('no_urut');
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