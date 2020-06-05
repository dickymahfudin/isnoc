<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCallDailysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_call_dailys', function (Blueprint $table) {
            $table->string('time_local', 20)->nullable()->default(null);
            $table->string('nojs', 8);
            $table->string('open_time', 20)->nullable()->default(null);
            $table->string('error', 20)->nullable()->default(null);
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
        Schema::dropIfExists('service_call_dailys');
    }
}