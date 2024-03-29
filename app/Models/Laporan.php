<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = "laporan"; //cek
    protected $primaryKey = "id"; //cek

    protected $fillable = [
        'id','id_pelapor','id_pengawas','tgl_masuk','no_inv_aset','tgl_selesai',
        'kat_layanan','jenis_layanan','det_layanan','foto',
        'tgl_awal_pengerjaan','tgl_akhir_pengerjaan','waktu_tambahan',
        'det_pekerjaan','ket_perkejaan'
    ]; 
}
