<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Laporanhist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $dtLap = Laporan::all();
        $dtLap = DB::table('laporan')
        ->join('laporanhist', 'laporan.id', '=', 'laporanhist.id_laporan')
        ->orderByDesc('laporanhist.created_at')
        ->limit(1)
        ->get([
            'laporan.no_inv_aset', 'laporan.tgl_masuk','laporan.jenis_layanan','laporan.kat_layanan',
            'laporan.tgl_awal_pengerjaan', 'laporan.tgl_akhir_pengerjaan',
            'laporan.det_layanan','laporanhist.status_laporan'
        ]);
        return view('pelapor.complaint', compact('dtLap')); 
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
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        if ($request->jenis_layanan === "Lainnya") {
            $laporan = Laporan::create([
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
                'no_inv_aset' => $request->no_inv_aset,
                'kat_layanan' => $request->kat_layanan,
                'jenis_layanan' => $request->jenis_layanan,
                'det_layanan' => $request->det_layanan,
                'tgl_masuk' => $tgl_masuk
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
