<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengawas;
use Barryvdh\DomPDF\Facade\Pdf;
use Facade\FlareClient\Stacktrace\File;

class PengawasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function profile ()
    {
        $dt = DB::table('pengawas')
        ->where('id','=', Auth::guard('pengawas')->user()->id)
        ->first();

        // dd($dt);

        return view('pengawas.profile',compact('dt'));
    }

    public function ttd(Request $request)
    {
        $ttd = $request->ttd;
        
        $getttd = DB::table('pengawas')
        ->select('ttd')
        ->where('id', Auth::guard('pengawas')->user()->id)
        ->first();

        $cekttd = $getttd->ttd;
        
        if($cekttd == ''){
            $nama_file_ttd = Auth::guard('pengawas')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName() ;
            $ttd->move(storage_path().'/app/public/img/pengawas', $nama_file_ttd);

            DB::table('pengawas')
            ->where('id', Auth::guard('pengawas')->user()->id)
            ->update([
                'ttd'  => $nama_file_ttd
            ]);
        } else if($cekttd != '' ){
            $nama_file_ttd = Auth::guard('pengawas')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName() ;
            $ttd->move(storage_path() . '/app/public/img/pengawas', $nama_file_ttd);

            DB::table('pengawas')
            ->where('id', Auth::guard('pengawas')->user()->id)
            ->update([
                'ttd'  => $nama_file_ttd
            ]);

            $old_ttd = $request->ttd_old;
            unlink(storage_path('app/public/img/pengawas/'.$old_ttd));

        }
        
        // dd($ttd, $filettd);
        return redirect('profile-pengawas');
    }

    public function akun()
    {
        $pelapor = DB::table('pelapor as p')
        ->leftJoin('laporan as l', 'p.id', '=', 'l.id_pelapor')
        ->leftJoin('admin as a','a.id','=','p.id_admin_tj')
        ->select(
            'p.id','p.nama','p.nipp','p.email','p.password',
            'p.jabatan','p.divisi','p.telepon','p.status','p.id_admin_tj',
            'a.nama as nama_admin','a.nipp as nipp_admin',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
        ->groupBy('p.id', 'p.nama', 'p.nipp', 'p.email', 'p.password', 
        'p.jabatan', 'p.divisi', 'p.telepon', 'p.status','a.nama','a.nipp',
            'p.id_admin_tj')
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
        ->leftJoin('laporan','laporan.id','=','laporanakhir.id_laporan')
        ->leftJoin('pelapor','laporan.id_pelapor','=','pelapor.id')
        ->leftJoin('admin','admin.id','=','laporan.id_admin')
        ->select(
            'pelapor.nama AS nama', 'admin.nama AS nama_admin', 'pelapor.divisi','pelapor.nipp',
            'laporan.id AS idlap', 'laporan.id AS idlap','laporan.jenis_layanan', 'laporan.waktu_tambahan',
            'laporan.kat_layanan','laporan.det_layanan', 'laporan.no_inv_aset', 'laporan.det_pekerjaan','laporan.ket_pekerjaan',
            'laporanakhir.no_ref',
            'laporanakhir.tanggal',
            'laporanakhir.bisnis_area',
            'laporanakhir.versi',
            'laporanakhir.halaman',
            'laporanakhir.nomor',
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
        )
        ->get();
        
        // dd($lap);
        return view('pengawas.laporan', compact('lap'));
    }

    public function cetak($idlap)
    {
        $dt = DB::table('laporanakhir')
        ->rightJoin('laporan','laporan.id','=','laporanakhir.id_laporan')
        ->leftJoin('admin','admin.id','=','laporan.id_admin')
        ->leftJoin('pelapor', 'pelapor.id','=', 'laporan.id_pelapor')
        ->leftJoin('pengawas','pengawas.id','=','laporan.id_pengawas')
        ->select(
            'pelapor.nama AS nama_pelapor', 'pelapor.divisi','pelapor.email','pelapor.telepon','pelapor.nipp as nipp_pelapor', 'pelapor.ttd as ttd_pelapor',
            'pengawas.nama as nama_pengawas','pengawas.nipp as nipp_pengawas', 'pengawas.ttd as ttd_pengawas',
            'admin.nama as nama_admin','admin.nipp as nipp_admin', 'admin.ttd as ttd_admin',
            'laporan.id as idlap','laporan.jenis_layanan','laporan.kat_layanan',
            'laporan.no_inv_aset', 'laporan.det_pekerjaan','laporan.ket_pekerjaan',
            'laporanakhir.no_ref',
            'laporanakhir.bisnis_area',
            'laporanakhir.versi',
            'laporanakhir.halaman',
            'laporanakhir.nomor',
            DB::raw("DATE_FORMAT(laporanakhir.tanggal, '%d-%m-%Y') AS tanggal"),
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%H:%i WIB') AS waktu_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%d-%m-%Y') AS tgl_selesai"),
            DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%H:%i WIB') AS waktu_selesai"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%H:%i WIB') AS waktu_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y') AS tgl_akhir_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%H:%i WIB') AS waktu_akhir_pengerjaan"),
        )
        ->where('laporan.id','=',$idlap)
        ->first();
        
        $pdf = Pdf::loadView('pengawas.cetakNew', compact('dt'))->setPaper('legal', 'portrait')->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "laporan.pdf"
        );
        // dd($dt);

        // return view('pengawas.cetak', compact('dt'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pjuser(Request $request, $id)
    {
        // $action         = $request->input('action');
        $id_admin_tj    = $request->id_admin_tj;

        DB::table('pelapor')
        ->where('id', $id)
        ->update([
            'id_admin_tj'  => $id_admin_tj,
            'status'       => 1 
        ]);
        return redirect('list-akun');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idlap)
    {
        $dtp = DB::table('laporanakhir')
        ->leftJoin('laporan', 'laporan.id', '=', 'laporanakhir.id_laporan')
        ->leftJoin('pelapor', 'laporan.id_pelapor', '=', 'pelapor.id')
        ->leftJoin('admin', 'admin.id', '=', 'laporan.id_admin')
        ->select(
            'pelapor.nama AS nama',
            'admin.nama AS nama_admin',
            'pelapor.divisi',
            'pelapor.nipp',
            'laporanakhir.id_laporan AS idlap',
            'laporan.jenis_layanan',
            'laporan.waktu_tambahan',
            'laporan.kat_layanan',
            'laporan.det_layanan',
            'laporan.no_inv_aset',
            'laporan.det_pekerjaan',
            'laporan.ket_pekerjaan',
            'laporanakhir.no_ref',
            'laporanakhir.tanggal',
            'laporanakhir.bisnis_area',
            'laporanakhir.versi',
            'laporanakhir.halaman',
            'laporanakhir.nomor',
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
        )
        ->where('laporan.id','=',$idlap)
        ->first();

        // dd($dtp);
        return view('pengawas.detail', compact('dtp'));
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
