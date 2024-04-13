<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetLaporan extends Model
{
    use HasFactory;
    protected $table = "detlaporan"; //cek
    protected $primaryKey = "id"; //cek

    protected $fillable = [
        'id', 'id_laporan','kat_layanan', 'jenis_layanan', 'det_layanan', 'foto',
        'det_pekerjaan', 'ket_perkejaan'
    ]; 
}
