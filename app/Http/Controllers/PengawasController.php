<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengawas;

class PengawasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function akun()
    {
        $pelapor = DB::table('pelapor as p')
        ->select(
            'p.id','p.nama','p.nipp','p.email','p.password',
            'p.jabatan','p.divisi','p.telepon',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
        ->leftJoin('laporan as l', 'p.id', '=', 'l.id_pelapor')
        ->groupBy('p.id', 'p.nama', 'p.nipp', 'p.email', 'p.password', 'p.jabatan', 'p.divisi', 'p.telepon')
        ->get();

        $it = DB::table('admin as t')
        ->select(
            't.id',
            't.nama',
            't.nipp',
            't.email',
            't.jabatan',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
        ->leftJoin('laporan as l', 't.id', '=', 'l.id_admin')
        ->groupBy('t.id','t.nama','t.nipp','t.email','t.jabatan',
        )->get();

        return view('pengawas.akun-user', compact('pelapor','it'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function laporan()
    {
        $lap = DB::table('laporanakhir')
        ->join('laporan','laporan.id','=','laporanakhir.id_laporan')
        ->join('pelapor','laporan.id_pelapor','=','pelapor.id')
        ->join('admin','admin.id','=','laporan.id_admin')
        ->select(
            'pelapor.nama AS nama', 'admin.nama AS nama_admin',
            'laporan.id AS idlap',
            'laporan.id AS idlap',
            'laporan.jenis_layanan',
            'laporan.waktu_tambahan',
            'laporan.kat_layanan',
            'laporan.det_layanan',
            'laporan.no_inv_aset', 'laporan.det_pekerjaan','laporan.ket_pekerjaan',
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
        )
        ->get();
        
        // dd($lap);
        return view('pengawas.laporan', compact('lap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
