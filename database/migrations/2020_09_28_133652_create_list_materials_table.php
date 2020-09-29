<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_materials', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 20)->nullable()->default(null);
            $table->string('serial', 20)->nullable()->default(null);
            $table->integer('jumlah_barang')->nullable()->default(null);
            $table->string('mitra', 10)->nullable()->default(null);
            $table->string('tanggal_keluar', 20)->nullable()->default(null);
            $table->string('tanggal_terima', 20)->nullable()->default(null);
            $table->string('tanggal_pemasangan', 20)->nullable()->default(null);
            $table->string('nojs', 8)->nullable()->default(null);
            $table->string('site', 20)->nullable()->default(null);
            $table->string('teknisi', 20)->nullable()->default(null);
            $table->string('status', 10)->nullable()->default(null);
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
        Schema::dropIfExists('list_materials');
    }
}