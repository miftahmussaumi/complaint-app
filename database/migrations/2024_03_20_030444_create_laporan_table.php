<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pelapor')->nullable();
            $table->bigInteger('id_pengawas')->nullable();
            $table->bigInteger('id_admin')->nullable(); 
            $table->string('no_inv_aset', 30)->nullable();
            $table->dateTime('tgl_selesai')->nullable();  
            $table->string('kat_layanan', 30)->nullable();
            $table->string('jenis_layanan', 30)->nullable();
            $table->text('det_layanan')->nullable();
            $table->dateTime('tgl_awal_pengerjaan')->nullable();
            $table->dateTime('tgl_akhir_pengerjaan')->nullable();
            $table->integer('waktu_tambahan')->nullable();
            $table->string('foto')->nullable();
            $table->text('det_pekerjaan')->nullable();
            $table->text('ket_pekerjaan')->nullable();
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
        Schema::dropIfExists('laporan');
    }
}
