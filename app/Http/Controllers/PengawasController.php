<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengawas;
use Barryvdh\DomPDF\Facade\Pdf;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Session; 

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
            // unlink(storage_path('app/public/img/pengawas/'.$old_ttd));

        }
        
        // dd($ttd, $filettd);
        return redirect('profile-pengawas');
    }

    public function akun()
    {
        $pelapor = DB::table('pelapor as p')
        ->leftJoin('laporan as l', 'p.id', '=', 'l.id_pelapor')
        ->select(
            'p.id','p.nama','p.nipp','p.email','p.password',
            'p.jabatan','p.divisi','p.telepon','p.status',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
        ->where('status', '=', 1)
        ->groupBy('p.id', 'p.nama', 'p.nipp', 'p.email', 'p.password', 
        'p.jabatan', 'p.divisi', 'p.telepon', 'p.status',)
        // ->orderByDesc('p.created_at')
        ->get();

        $acc    = DB::table('pelapor')->where('status','=',0)->get();
        $cacc   = count($acc);

        $it = DB::table('teknisi as t')
        ->select(
            't.id',
            't.nama',
            't.nipp',
            't.email',
            't.jabatan',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
        ->leftJoin('laporan as l', 't.id', '=', 'l.id_teknisi')
        ->groupBy('t.id','t.nama','t.nipp','t.email','t.jabatan',
        )->get();

        // dd($acc);

        return view('pengawas.akun-user', compact('pelapor','it','cacc','acc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function laporan()
    {
        $lap = DB::table('laporan')
        ->leftJoin('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
        ->leftJoin('pelapor','pelapor.id','=','laporan.id_pelapor')
        ->leftJoin('pengawas','pengawas.id','=','laporan.id_pengawas')
        ->select(
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tgl_selesai"),
            'laporan.no_inv_aset',
            'laporan.waktu_tambahan',
            'laporan.status_terakhir',
            'laporan.id AS id',
            'id_teknisi','lap_no_ref as no_ref','lap_bisnis_area as bisnis_area',
            'teknisi.nama as nama_teknisi',
            'laporan.lap_no_ref','laporan.lap_tanggal','laporan.lap_bisnis_area','laporan.lap_versi','laporan.lap_halaman','laporan.lap_nomor',
            'pelapor.nama AS nama_pelapor',
            'pelapor.divisi',
            'pelapor.email',
            'pelapor.telepon',
            'pelapor.nipp as nipp_pelapor',
            'pengawas.ttd'
        )
        ->where('laporan.status_terakhir','=','Manager')
        ->orderBy('tgl_masuk')
        ->get();
        
        // dd($lap);
        return view('pengawas.laporan', compact('lap'));
    }

    public function cetak($idlap)
    {
        $detlap = DB::table('detlaporan')
        ->where('id_laporan','=',$idlap)
        ->get();

        $lap = DB::table('laporan')
        ->leftJoin('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
        ->leftJoin('pelapor', 'pelapor.id', '=', 'laporan.id_pelapor')
        ->leftJoin('pengawas', 'pengawas.id', '=', 'laporan.id_pengawas')
        ->select(
            'pelapor.nama AS nama_pelapor',
            'pelapor.divisi',
            'pelapor.email',
            'pelapor.telepon',
            'pelapor.nipp as nipp_pelapor',
            'pelapor.ttd as ttd_pelapor',
            'pengawas.nama as nama_pengawas',
            'pengawas.nipp as nipp_pengawas',
            'pengawas.ttd as ttd_pengawas',
            'teknisi.nama as nama_teknisi',
            'teknisi.nipp as nipp_teknisi',
            'teknisi.ttd as ttd_teknisi',
            'laporan.id as idlap',
            'laporan.no_inv_aset',
            'laporan.lap_no_ref as no_ref',
            'laporan.lap_bisnis_area as bisnis_area',
            'laporan.lap_versi as versi',
            'laporan.lap_halaman as halaman',
            'laporan.lap_nomor as nomor',
            DB::raw("DATE_FORMAT(laporan.lap_tanggal, '%d-%m-%Y') AS tanggal"),
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%H:%i WIB') AS waktu_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%d-%m-%Y') AS tgl_selesai"),
            DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%H:%i WIB') AS waktu_selesai"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%H:%i WIB') AS waktu_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y') AS tgl_akhir_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%H:%i WIB') AS waktu_akhir_pengerjaan"),
        )
            ->where('laporan.id', '=', $idlap)
            ->first();

        $pdf = Pdf::loadView('pengawas.cetakNew', compact('lap', 'detlap'))->setPaper('legal', 'portrait')->output();

        return response()->streamDownload(
            fn () => print($pdf),
            "laporan.pdf"
        );
        // dd($jml_ins, $jml_trou);
        // dd($detlap);

        // return view('test', compact('lap','detlap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accakun(Request $request, $id)
    {
        $action         = $request->action;
        // $id_admin_tj    = $request->id_admin_tj;

        if ($action == 'accept') {
            DB::table('pelapor')
            ->where('id', $id)
            ->update([
                'status'       => 1 
            ]);
        }

        Session::flash('success'); 

        return redirect('list-akun');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $detlaporan = DB::table('detlaporan')
        ->where('id_laporan', '=', $id)->get();

        $laporan = DB::table('laporan')
        ->leftjoin('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
        ->where('laporan.id', '=', $id)
            ->select(
                DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d %M %Y, %H:%i WIB') AS tgl_awal_pengerjaan"),
                DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
                'no_inv_aset',
                'waktu_tambahan',
                'id_teknisi',
                'teknisi.nama',
                'status_terakhir',
                'laporan.id as id'
            )
            ->first();

        // dd($dtp);
        return view('pengawas.detail', compact('laporan','detlaporan'));
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
