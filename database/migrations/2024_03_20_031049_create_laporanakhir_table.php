<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanakhirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporanakhir', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_laoran')->nullable();   
            $table->string('no_ref', 50)->nullable();
            $table->dateTime('tanggal_akhir')->nullable();  
            $table->string('bisnis_area', 50)->nullable();
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
        Schema::dropIfExists('laporanakhir');
    }
}
