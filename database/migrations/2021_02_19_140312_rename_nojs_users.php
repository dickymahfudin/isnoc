<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNojsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nojs_users', function (Blueprint $table) {
            $table->renameColumn('id_lvdvsat', 'id_lvd_vsat');
            $table->renameColumn('id_batvolt', 'id_batt_volt');
            $table->renameColumn('id_vsatcurr', 'id_vsat_curr');
            $table->renameColumn('id_btscurr', 'id_bts_curr');
            $table->integer('gs')->nullable()->default(null)->after('id_bts_curr');
            $table->string('darat', 30)->nullable()->default(null)->after('gs');
            $table->string('laut', 30)->nullable()->default(null)->after('darat');
            $table->string('udara', 30)->nullable()->default(null)->after('laut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nojs_users', function (Blueprint $table) {
            $table->renameColumn('id_lvd_vsat', 'id_lvdvsat');
            $table->renameColumn('id_batt_volt', 'id_batvolt');
            $table->renameColumn('id_vsat_curr', 'id_vsatcurr');
            $table->renameColumn('id_bts_curr', 'id_btscurr');
            $table->dropColumn('pms_state');

            $table->dropColumn('gs');
            $table->dropColumn('darat');
            $table->dropColumn('laut');
            $table->dropColumn('udara');
        });
    }
}