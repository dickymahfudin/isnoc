<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPmsStateColumnToServiceCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_calls', function (Blueprint $table) {
            $table->string('pms_state', 16)->nullable()->default(null)->after('error');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_calls', function (Blueprint $table) {
            $table->dropColumn('pms_state');
        });
    }
}