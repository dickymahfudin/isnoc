<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_calls', function (Blueprint $table) {
            $table->string('service_id', 20)->primary();
            $table->string('nojs');
            $table->string('open_time', 20);
            $table->string('closed_time', 20);
            $table->string('error', 20);
            $table->string('status', 20);
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
        Schema::dropIfExists('service_calls');
    }
}