<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaPrtgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_prtgs', function (Blueprint $table) {
            $table->string('time_local', 20);
            $table->string('nojs', 8);
            $table->string('site', 20);
            $table->float('batt_voltage', 5)->nullable()->default(null);
            $table->float('lvd1_vsat', 5)->nullable()->default(null);
            $table->float('vsat_current', 5)->nullable()->default(null);
            $table->float('bts_current', 5)->nullable()->default(null);
            $table->float('ping', 5)->nullable()->default(null);
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
        Schema::dropIfExists('sla_prtgs');
    }
}