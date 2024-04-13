<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengawas;
use Carbon\Carbon;
use App\Models\Laporanakhir;
use App\Models\Laporanhist;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dtLap = DB::table('laporan')
        ->leftJoin('teknisi','teknisi.id','=','laporan.id_teknisi')
        ->select(
            'no_inv_aset',
            'waktu_tambahan',
            'status_terakhir',
            'tgl_akhir_pengerjaan AS deadline',
            'laporan.id AS id','id_teknisi','teknisi.nama as nama_teknisi',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
        )
        ->orderBy('tgl_masuk')
        ->get();

        $teknisi = DB::table('teknisi')->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'

        return view('admin.laporan', compact('dtLap','teknisi'));
    }

    /**
     * Show the form for creating a new resource.
     *
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
                'status_terakhir','laporan.id as id'
            )
            ->first();

        // Dapatkan nomor referensi terakhir dari database
        $lastReferenceNumber = DB::table('laporan')
        ->whereNotNull('lap_no_ref')
        ->orderBy('id', 'desc')
            ->value('lap_no_ref');

        // Dapatkan tanggal hari ini dengan format "dd/mm/yyyy"
        $tanggal = Carbon::now()->format('d/m/Y');

        // Parsing nomor referensi terakhir untuk mendapatkan bagian nomor auto-increment
        $autoIncrement = 1;
        if ($lastReferenceNumber) {
            $lastReferenceNumberParts = explode('/', $lastReferenceNumber);
            $autoIncrement = intval($lastReferenceNumberParts[0]) + 1;
        }

        // Format nomor referensi baru
        $nomorReferensi = sprintf('%03d', $autoIncrement) . '/' . $tanggal;

        // dd($nomorReferensi);

        return view('admin.laporan-detail', compact('laporan', 'detlaporan', 'nomorReferensi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function teknisi(Request $request, $id)
    {
        $id_teknisi    = $request->id_teknisi;

        DB::table('laporan')
        ->where('id', $id)
        ->update([
            'id_teknisi'  => $id_teknisi,
        ]);

        return redirect('laporan-admin');
    }

    public function profile()
    {
        $dt = DB::table('admin')
        ->where('id', '=', Auth::guard('admin')->user()->id)
        ->first();

        // dd($dt);

        return view('it.profile', compact('dt'));
    }

    public function ttd(Request $request)
    {
        $ttd = $request->ttd;

        $getttd = DB::table('admin')
        ->select('ttd')
        ->where('id', Auth::guard('admin')->user()->id)
            ->first();

        $cekttd = $getttd->ttd;

        if ($cekttd == '') {
            $nama_file_ttd = Auth::guard('admin')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/admin', $nama_file_ttd);

            DB::table('admin')
            ->where('id', Auth::guard('admin')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);
        } else if ($cekttd != '') {
            $nama_file_ttd = Auth::guard('admin')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/admin', $nama_file_ttd);

            DB::table('admin')
            ->where('id', Auth::guard('admin')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);

            // $old_ttd = $request->ttd_old;
            // unlink(storage_path('app/public/img/admin/' . $old_ttd));
        }

        // dd($ttd, $filettd);
        return redirect('profile-admin');
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendtoManager(Request $request, $id)
    {
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('laporan')
        ->where('id', $id)
        ->update([
            'lap_no_ref'        => $request->lap_no_ref,
            'lap_tanggal'       => $request->lap_tanggal,
            'lap_bisnis_area'   => $request->lap_bisnis_area,
            'lap_versi'         => $request->lap_versi,
            'lap_halaman'       => $request->lap_halaman,
            'lap_nomor'         => $request->lap_nomor,
            'status_terakhir'   => 'Manager'
        ]);

        // dd($request->all());
        return redirect('laporan-admin');
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
