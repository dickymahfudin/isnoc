<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjnLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajn_loggers', function (Blueprint $table) {
            $table->string('time_local', 20);
            $table->string('site', 8);
            $table->integer('load1')->nullable()->default(null);
            $table->integer('load2')->nullable()->default(null);
            $table->integer('edl1')->nullable()->default(null);
            $table->integer('edl2')->nullable()->default(null);
            $table->integer('edl3')->nullable()->default(null);
            $table->integer('pv_volt1')->nullable()->default(null);
            $table->integer('pv_curr1')->nullable()->default(null);
            $table->integer('batt_volt1')->nullable()->default(null);
            $table->integer('pv_volt2')->nullable()->default(null);
            $table->integer('pv_curr2')->nullable()->default(null);
            $table->integer('batt_volt2')->nullable()->default(null);
            $table->string('pms_state', 16)->nullable()->default(null);
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
        Schema::dropIfExists('ajn_loggers');
    }
}