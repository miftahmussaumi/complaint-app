<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Laporanhist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dtLap = DB::table('laporan')
            ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
                $join->on('laporan.id', '=',
                    'latest_laporanhist.id_laporan'
                );
            })
            ->leftJoin('laporanhist', function ($join) {
                $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
            })
            ->leftJoin('pelapor','laporan.id_pelapor','=','pelapor.id')
            ->select(
                'pelapor.nama','pelapor.divisi','pelapor.telepon','pelapor.email',
                'laporan.id AS idlap','laporan.jenis_layanan','laporan.tgl_masuk',
                'laporan.kat_layanan','laporan.det_layanan','laporan.no_inv_aset',
                'laporan.tgl_awal_pengerjaan',
                'laporan.tgl_akhir_pengerjaan',
                'laporanhist.tanggal AS tanggal_status_terbaru', 'laporanhist.keterangan',
                'laporanhist.status_laporan AS status_terbaru','laporanhist.id AS idhist'
            )
            ->where('laporanhist.status_laporan', '!=', 'Selesai')
            ->get();

        return view('pelapor.complaint', compact('dtLap')); 
    }

    public function indexit()
    {
        $dataIT = DB::table('laporan')
            ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
                $join->on(
                    'laporan.id',
                    '=',
                    'latest_laporanhist.id_laporan'
                );
            })
            ->leftJoin('laporanhist', function ($join) {
                $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                    ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
            })
            ->leftJoin('pelapor', 'laporan.id_pelapor', '=', 'pelapor.id')
            ->select(
                'pelapor.nama','pelapor.divisi','pelapor.telepon','pelapor.email',
                'laporan.id AS idlap','laporan.jenis_layanan','laporan.tgl_masuk',
                'laporan.kat_layanan','laporan.det_layanan','laporan.no_inv_aset',
                'laporan.tgl_awal_pengerjaan',
                'laporan.tgl_akhir_pengerjaan',
                'laporanhist.tanggal AS tanggal_status_terbaru',
                'laporanhist.status_laporan AS status_terbaru','laporanhist.id AS idhist'
            )
            ->where('laporanhist.status_laporan', '=', 'Pengajuan')
            ->orWhere('laporanhist.status_laporan', '=', 'Diproses')
            ->orWhere('laporanhist.status_laporan', '=', 'CheckedU')
            ->orWhere('laporanhist.status_laporan', '=', 'CheckedIT')
            ->get();

        return view('it.comp', compact('dataIT')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(Request $request)
    {
        $tgl_masuk = today()->format('Y-m-d H:i:s');

        if ($request->jenis_layanan === "Lainnya") {
            $laporan = Laporan::create([
                'id_pelapor'    => Auth::guard('pelapor')->user()->id,
                'no_inv_aset'   => $request->no_inv_aset,
                'kat_layanan'   => $request->kat_layanan,
                'jenis_layanan' => $request->layanan_lain,
                'det_layanan'   => $request->det_layanan,
                'tgl_masuk'     => $tgl_masuk
            ]);
            $id_laporan = $laporan->id;
            Laporanhist::create([
                'id_laporan'    => $id_laporan,
                'status_laporan'=> 'Pengajuan',
                'tanggal'       => $tgl_masuk
            ]);
        } else {
            $laporan = Laporan::create([
                'id_pelapor'    => Auth::guard('pelapor')->user()->id,
                'no_inv_aset'   => $request->no_inv_aset,
                'kat_layanan'   => $request->kat_layanan,
                'jenis_layanan' => $request->jenis_layanan,
                'det_layanan'   => $request->det_layanan,
                'tgl_masuk'     => $tgl_masuk
            ]);
            $id_laporan = $laporan->id;
            Laporanhist::create([
                'id_laporan'    => $id_laporan,
                'status_laporan' => 'Pengajuan',
                'tanggal'       => $tgl_masuk
            ]);
        }
        return redirect('comp');
        // dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editit(Request $request, $idhist)
    {
        // $dateRange = $request->tgl_pengerjaan;
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');
        $keterangan = $request->keterangan;
        $id_laporan = $request->id_laporan;
        $status_laporan = $request->status_laporan;

        $tgl_awal = $request->tgl_awal;
        $waktu_awal = $request->waktu_awal;
        $waktu_konversi_awal = date("H:i:s", strtotime($waktu_awal));

        $tgl_akhir = $request->tgl_akhir;
        $waktu_akhir = $request->waktu_akhir;
        $waktu_konversi_akhir = date("H:i:s", strtotime($waktu_akhir));

        $awal_pengerjaan = $tgl_awal . ' ' . $waktu_konversi_awal;
        $tgl_awal_pengerjaan = date('Y-m-d H:i:s', strtotime($awal_pengerjaan));

        $akhir_pengerjaan = $tgl_akhir . ' ' . $waktu_konversi_akhir;
        $tgl_akhir_pengerjaan = date('Y-m-d H:i:s', strtotime($akhir_pengerjaan));

        if($status_laporan == 'Pengajuan') {
            DB::table('laporan')
            ->where('id', $id_laporan)
            ->update([
                'tgl_awal_pengerjaan'  => $tgl_awal_pengerjaan,
                'tgl_akhir_pengerjaan'  => $tgl_akhir_pengerjaan,
            ]);
    
            Laporanhist::create([
                'id_laporan'        => $id_laporan,
                'status_laporan'    => 'Diproses',
                'tanggal'           => $tgl_masuk,
            ]);
        } else if ($status_laporan == 'Diproses'){
            Laporanhist::create([
                'id_laporan'        => $id_laporan,
                'status_laporan'    => 'CheckedU',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => $keterangan
            ]);
        }

        // return redirect('it');
        // dd($tgl_masuk, $tgl_awal_pengerjaan, $tgl_akhir_pengerjaan, $id_laporan);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $idlap)
    {
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        Laporanhist::create([
            'id_laporan'        => $idlap,
            'status_laporan'    => 'Selesai',
            'tanggal'           => $tgl_masuk,
        ]);

        DB::table('laporan')
        ->where('id', $idlap)
        ->update([
            'tgl_selesai'  => $tgl_masuk,
        ]);

        return redirect('comp');
        // dd($tgl_masuk, $idlap);

    }

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
