<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Laporanakhir;
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

    public function dashU()
    {
        $dtLap = DB::table('laporan')
        ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
            $join->on( 'laporan.id','=','latest_laporanhist.id_laporan'
            );
        })
            ->leftJoin('laporanhist', function ($join) {
                $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
            })
            ->leftJoin('pelapor', 'laporan.id_pelapor', '=', 'pelapor.id')
            ->select('laporanhist.status_laporan AS status_terbaru')
            ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
            ->orderBy('laporan.tgl_masuk')
            ->get();

        $selesai    = $dtLap->where('status_terbaru', 'Selesai')->count();
        $check      = $dtLap->where('status_terbaru', 'CheckedU')->count();
        $proses     = $dtLap->where('status_terbaru', 'Diproses')->count();
        $all        = $dtLap->count();
        
        // dd($statusSelesaiCount);
        return view('pelapor.dashboard', compact('selesai','check','proses','all'));
    }
    public function index()
    {
        $dtLap = DB::table('laporan')
            ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
                $join->on('laporan.id', '=', 'latest_laporanhist.id_laporan');
            })
            ->leftJoin('laporanhist', function ($join) {
                $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
            })
            ->leftJoin('pelapor','laporan.id_pelapor','=','pelapor.id')
            ->select(
                'pelapor.nama','pelapor.divisi','pelapor.telepon','pelapor.email',
                'laporan.id AS idlap','laporan.jenis_layanan','laporan.waktu_tambahan',
                DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
                'laporan.kat_layanan','laporan.det_layanan','laporan.no_inv_aset',
                DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
                DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
                'laporan.tgl_akhir_pengerjaan AS deadline',
                'laporanhist.tanggal AS tanggal_status_terbaru', 'laporanhist.keterangan',
                'laporanhist.status_laporan AS status_terbaru','laporanhist.id AS idhist'
            )
            ->whereNotIn('laporanhist.status_laporan', ['Selesai', 'Dibatalkan'])
            ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
            ->orderBy('laporan.tgl_masuk')
            ->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'

        return view('pelapor.complaint', compact('dtLap')); 
    }

    public function indexit()
    {
        $dtLap = DB::table('laporan')
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
                'pelapor.nama',
                'pelapor.divisi',
                'pelapor.telepon',
                'pelapor.email',
                'laporan.id AS idlap',
                'laporan.jenis_layanan',
                'laporan.waktu_tambahan',
                DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
                'laporan.kat_layanan',
                'laporan.det_layanan',
                'laporan.no_inv_aset',
                DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
                DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
                'laporan.tgl_akhir_pengerjaan AS deadline',
                'laporanhist.tanggal AS tanggal_status_terbaru',
                'laporanhist.keterangan',
                'laporanhist.status_laporan AS status_terbaru',
                'laporanhist.id AS idhist'
            )
            ->whereNotIn('laporanhist.status_laporan', ['Selesai', 'Dibatalkan'])
            ->where(function ($query) {
                $query->where('laporan.id_admin', Auth::guard('admin')->user()->id)
                    ->orWhereNull('laporan.id_admin');
            })
            ->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'
        // dd(Auth::guard('admin')->user()->id);
        return view('it.comp', compact('dtLap')); 
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

        if ($request->jenis_layanan === "Lainnya") {
            $laporan = Laporan::create([
                'id_pelapor'    => Auth::guard('pelapor')->user()->id,
                'no_inv_aset'   => $request->no_inv_aset,
                'kat_layanan'   => $request->kat_layanan,
                'jenis_layanan' => $request->layanan_lain,
                'det_layanan'   => $request->det_layanan,
                'tgl_masuk'     => $tgl_masuk,
                'tgl_awal_pengerjaan'  => $tgl_awal_pengerjaan,
                'tgl_akhir_pengerjaan'  => $tgl_akhir_pengerjaan,
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
                'tgl_masuk'     => $tgl_masuk,
                'tgl_awal_pengerjaan'  => $tgl_awal_pengerjaan,
                'tgl_akhir_pengerjaan'  => $tgl_akhir_pengerjaan,
            ]);
            $id_laporan = $laporan->id;
            Laporanhist::create([
                'id_laporan'    => $id_laporan,
                'status_laporan' => 'Pengajuan',
                'tanggal'       => $tgl_masuk
            ]);
        }
        return redirect('comp');
        // dd($tgl_masuk, $tgl_awal_pengerjaan, $tgl_akhir_pengerjaan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tambahwaktu(Request $request, $idlap)
    {
        $waktu_tambahan = $request->waktu_tambahan;
        $keterangan = $request->keterangan;
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        Laporanhist::create([
            'id_laporan'        => $idlap,
            'status_laporan'    => 'reqAddTime',
            'tanggal'           => $tgl_masuk,
            'keterangan'        => $keterangan
        ]);

        DB::table('laporan')
        ->where('id', $idlap)
        ->update([
            'waktu_tambahan'  => $waktu_tambahan,
            'id_admin'        => Auth::guard('admin')->user()->id
        ]);

        return redirect('it');

        // dd($waktu_tambahan, $keterangan);
    }

    public function update(Request $request, $idlap)
    {
        $action     = $request->input('action');
        $tgl_masuk  = Carbon::now()->format('Y-m-d H:i:s');

        if ($action === 'accept') {
            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Diproses',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Penambahan waktu diterima'
            ]);
        } elseif ($action === 'reject') {
            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Dibatalkan',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Permintaan penambahan waktu tidak diterima'
            ]);
            DB::table('laporan')
            ->where('id', $idlap)
            ->update([
                'waktu_tambahan'  => DB::raw('NULL'),
            ]);
        } elseif ($action === 'finished') {
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

            Laporanakhir::create([
                'id_laporan'        => $idlap,
                'tgl_akhir'         => $tgl_masuk,
            ]);
            
        }


        return redirect('comp');
        // dd($action);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editit(Request $request, $idlap)
    {
        // $dateRange = $request->tgl_pengerjaan;
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');
        $action     = $request->input('action');
        
        if ($action === 'process'){
            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Diproses',
                'tanggal'           => $tgl_masuk,
            ]);
        } else if ($action === 'delete'){
            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Dibatalkan',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Pengajuan penghapusan permintaan disetujui oleh Admin IT, laporan dibatalkan.'
            ]);
        }

        return redirect('it');
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

    public function detail($idlap)
    {
        $dtLap = DB::table('laporan')
        ->select(
            'laporan.id AS idlap',
            'laporan.jenis_layanan',
            'laporan.waktu_tambahan',
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d-%m-%Y') AS tgl_masuk"),
            'laporan.kat_layanan',
            'laporan.det_layanan',
            'laporan.no_inv_aset',
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d-%m-%Y (%H:%i WIB)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d-%m-%Y  (%H:%i WIB)') AS tgl_akhir_pengerjaan"),
            'laporan.tgl_akhir_pengerjaan AS deadline',
        )
            ->where('laporan.id','=',$idlap)
            ->get();

        return view('it.comp-detail', compact('dtLap'));
    }

    public function pelayananS(Request $request, $idlap)
    {
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        $det_pekerjaan = $request->det_pekerjaan;
        $ket_pekerjaan = $request->ket_pekerjaan;

        Laporanhist::create([
            'id_laporan'        => $idlap,
            'status_laporan'    => 'CheckedU',
            'tanggal'           => $tgl_masuk,
        ]);

        if ($ket_pekerjaan != null) {
            DB::table('laporan')
            ->where('id', $idlap)
            ->update([
                'det_pekerjaan' => $det_pekerjaan,
                'ket_pekerjaan' => $ket_pekerjaan,
                'id_admin'      => Auth::guard('admin')->user()->id 
            ]);
        } else {
            DB::table('laporan')
            ->where('id', $idlap)
            ->update([
                'det_pekerjaan' => $det_pekerjaan,
                'id_admin'      => Auth::guard('admin')->user()->id 
            ]);
        }

        return redirect('it');
        // dd($det_pekerjaan,$ket_pekerjaan);

    }

    public function historyU($id)
    {
        $data = DB::table('laporan')
        ->where('laporan.id_pelapor', '=', $id)
        ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
        ->orderBy('laporan.tgl_masuk', 'desc')
        ->select(
            'laporan.id AS id',
            'no_inv_aset',
            'tgl_selesai',
            'kat_layanan',
            'jenis_layanan',
            'det_layanan',
            'waktu_tambahan',
            'laporan.foto',
            'det_pekerjaan',
            'ket_pekerjaan',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
        )
        ->where('laporanhist.status_laporan', '=', 'Selesai')
        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan')
        ->get();

        foreach ($data as $laporan) {
            $laporan->history = DB::table('laporanhist')
            ->where('id_laporan', $laporan->id)
                ->orderBy('tanggal', 'desc')
                ->select(
                    'id',
                    'id_laporan',
                    'status_laporan',
                    'keterangan',
                    'foto',
                    DB::raw("DATE_FORMAT(tanggal, '%d %M %Y (%H:%i)') AS tanggal"),
                )
                ->get();
        }

        return view('pelapor.history-u', compact('data'));
    }

    public function historyA($id)
    {
        $data = DB::table('laporan')
        ->where('laporan.id_admin', '=', $id)
        ->join('laporanhist','laporanhist.id_laporan','=','laporan.id')
        ->orderBy('laporan.tgl_masuk', 'desc')
        ->select(
            'laporan.id AS id','no_inv_aset','tgl_selesai','kat_layanan',
            'jenis_layanan','det_layanan',
            'waktu_tambahan','laporan.foto',
            'det_pekerjaan','ket_pekerjaan',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
        )
        ->where('laporanhist.status_laporan','=','Selesai')
        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan')
        ->get();

        foreach ($data as $laporan) {
            $laporan->history = DB::table('laporanhist')
            ->where('id_laporan', $laporan->id)
            ->orderBy('tanggal', 'desc')
            ->select(
                'id','id_laporan','status_laporan','keterangan','foto',
                DB::raw("DATE_FORMAT(tanggal, '%d %M %Y (%H:%i)') AS tanggal"),
            )
            ->get();
        }

        return view('it.history-it', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($idlap)
    {
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        Laporanhist::create([
            'id_laporan'        => $idlap,
            'status_laporan'    => 'ReqHapus',
            'tanggal'           => $tgl_masuk,
            'keterangan'        => 'User mengajukan permintaan untuk menghapus laporan'
        ]);

        return redirect('comp');
    }
}
