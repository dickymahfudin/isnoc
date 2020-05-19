<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backup_loggers', function (Blueprint $table) {
            $table->string('time_local', 20);
            $table->string('nojs', 8);
            $table->integer('eh1')->nullable()->default(null);
            $table->integer('eh2')->nullable()->default(null);
            $table->integer('vsat_curr')->nullable()->default(null);
            $table->integer('bts_curr')->nullable()->default(null);
            $table->integer('load3')->nullable()->default(null);
            $table->integer('batt_volt1')->nullable()->default(null);
            $table->integer('batt_volt2')->nullable()->default(null);
            $table->integer('edl1')->nullable()->default(null);
            $table->integer('edl2')->nullable()->default(null);
            $table->string('pms_state', 16)->nullable()->default(null);
            $table->timestamps();

            $table->foreign('nojs')->references('nojs')->on('nojs_users')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backup_loggers');
    }
}