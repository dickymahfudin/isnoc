<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSitePemasanganToListMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_materials', function (Blueprint $table) {
            $table->string('site_pemasangan', 20)->after('teknisi')->nullable()->default(null);
            $table->renameColumn('site', 'site_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_materials', function (Blueprint $table) {
            $table->dropColumn('site_pemasangan');
            $table->renameColumn('site_stock', 'site');
        });
    }
}