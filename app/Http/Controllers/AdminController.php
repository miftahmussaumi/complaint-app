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

    public function dashboard()
    {
        $dtLap = DB::table('laporan')
        ->select('status_terakhir')
        ->orderBy('laporan.tgl_masuk')
        ->get();

        $open      = $dtLap->where('status_terakhir', 'Pengajuan')->count();
        $proses     = $dtLap->where('status_terakhir', 'Diproses')->count();
        $selesai    = $dtLap->where('status_terakhir', 'Selesai')->count();
        $all        = $dtLap->count();

        return view('admin.dashboard', compact('selesai','open','proses'));
    }

    public function index()
    {
        $dtLap = DB::table('laporan')
        ->leftJoin('teknisi','teknisi.id','=','laporan.id_teknisi')
        ->leftJoin('pelapor','pelapor.id','=','laporan.id_pelapor')
        ->select(
            'no_inv_aset',
            'waktu_tambahan',
            'status_terakhir',
            'tgl_akhir_pengerjaan AS deadline',
            'laporan.id AS id','id_teknisi','teknisi.nama as nama_teknisi',
            'laporan.tgl_masuk','pelapor.nama as nama_pelapor',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk_f"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
        )
        ->orderBy('laporan.tgl_masuk','asc')
        ->whereNotIn('status_terakhir', ['Selesai', 'Dibatalkan', 'Manager'])
        ->get();

        $teknisi = DB::table('teknisi')->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'

        return view('admin.laporan', compact('dtLap','teknisi'));
    }

    public function manager ()
    {
        $dtLap = DB::table('laporan')
        ->leftJoin('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
        ->leftJoin('pelapor', 'pelapor.id', '=', 'laporan.id_pelapor')
        ->select(
            'no_inv_aset',
            'waktu_tambahan',
            'status_terakhir',
            'tgl_akhir_pengerjaan AS deadline',
            'laporan.id AS id',
            'id_teknisi',
            'teknisi.nama as nama_teknisi',
            'laporan.tgl_masuk',
            'pelapor.nama as nama_pelapor',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk_f"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
        )
        ->where('status_terakhir','=','manager')
        ->orderBy('laporan.tgl_masuk', 'asc')
        ->get();

        foreach ($dtLap as $laporan) {
            $laporan->history = DB::table('laporanhist')
            ->where('id_laporan', $laporan->id)
                ->orderBy('tanggal', 'desc')
                ->select(
                    'id',
                    'id_laporan',
                    'status_laporan',
                    'keterangan',
                    'foto',
                    DB::raw("DATE_FORMAT(tanggal, '%d %M %Y (%H:%i:%s)') AS tanggal"),
                )
                ->get();
        }

        return view('admin.laporan-manager', compact('dtLap'));

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
        ->leftJoin('pelapor', 'pelapor.id', '=', 'laporan.id_pelapor')
        ->where('laporan.id', '=', $id)
            ->select(
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d %M %Y, %H:%i WIB') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
                'no_inv_aset',
                'waktu_tambahan',
                'id_teknisi',
                'teknisi.nama',
                'status_terakhir','laporan.id as id','pelapor.nama as nama_pelapor', 'pelapor.jabatan as jabatan_pelapor'
            )
            ->first();

        // Dapatkan nomor referensi terakhir dari database
        $lastReferenceNumber = DB::table('laporan')
        ->whereNotNull('lap_no_ref')
        ->orderBy('id', 'desc')
        ->value('lap_no_ref');

        $tanggal = Carbon::now()->format('m/Y');
        $bulan = Carbon::now()->format('m');

        // Code Auto Increament
        $autoIncrement = 1;
        if ($lastReferenceNumber) {
            $lastReferenceNumberParts = explode('/', $lastReferenceNumber);
            // Periksa jika masih dibulan yang sama
            if ($lastReferenceNumberParts[1] === $bulan) {
                $autoIncrement = intval($lastReferenceNumberParts[0]) + 1;
            }
        }
        $nomorReferensi = sprintf('%03d', $autoIncrement) . '/' . $tanggal;

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
            'id_pengawas'       => 1,
            'lap_no_ref'        => $request->lap_no_ref,
            'lap_tanggal'       => $request->lap_tanggal,
            'lap_bisnis_area'   => $request->lap_bisnis_area,
            'lap_versi'         => $request->lap_versi,
            'lap_halaman'       => $request->lap_halaman,
            'lap_nomor'         => $request->lap_nomor,
            'status_terakhir'   => 'Manager'
        ]);

        Laporanhist::create([
            'id_laporan'        => $id,
            'status_laporan'    => 'Manager',
            'tanggal'           => $tgl_masuk,
            'Keterangan'        => 'Laporan dikirimkan ke Manager'
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
    public function history(Request $request)
    {
        $filter = $request->filter;
        $tgl_masuk = $request->tgl_masuk;
        $tgl_masuk_f = $request->tgl_masuk_f;
        $tgl_selesai = $request->tgl_selesai;
        $tgl_selesai_f = $request->tgl_selesai_f;
        $no_inv_aset = $request->no_inv_aset;
        $kat_layanan = $request->kat_layanan;

        if ($filter == null) {
            $data = DB::table('laporan')
            ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
            ->join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
            ->select(
                'laporan.id AS id',
                'no_inv_aset',
                'tgl_selesai',
                'waktu_tambahan',
                'teknisi.nama as nama_teknisi',
                DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                DB::raw("DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tgl_selesai"),
                DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
                DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
                'laporan.lap_no_ref'
            )
                ->orderBy('laporanhist.tanggal', 'desc')
                ->where(function ($query) {
                    $query->where('laporanhist.status_laporan', '=', 'Selesai')
                    ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                })
                ->where('laporan.status_terakhir', '!=', 'Manager')
                ->get();
        } else {
            if ($kat_layanan != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                ->join('detlaporan', 'detlaporan.id_laporan', '=', 'laporan.id')
                ->join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
                ->orderBy('laporan.tgl_masuk', 'desc')
                ->select(
                    'laporan.id AS id',
                    'no_inv_aset',
                    'tgl_selesai',
                    'kat_layanan',
                    'jenis_layanan',
                    'det_layanan',
                    'waktu_tambahan',
                    'detlaporan.foto',
                    'det_pekerjaan',
                    'ket_pekerjaan',
                    'kat_layanan',
                    'jenis_layanan',
                    'teknisi.nama as nama_teknisi',
                    DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                    DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
                    DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
                    'laporan.lap_no_ref'
                )
                    ->where(function ($query) {
                        $query->where('laporanhist.status_laporan', '=', 'Selesai')
                        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                    })
                    ->where('detlaporan.kat_layanan', '=', $kat_layanan)
                    ->where('laporan.status_terakhir', '!=', 'Manager')
                    ->get();
                // dd($data);
            } else if ($no_inv_aset != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                ->join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
                ->orderBy('laporan.tgl_masuk', 'desc')
                ->select(
                    'laporan.id AS id',
                    'no_inv_aset',
                    'tgl_selesai',
                    'waktu_tambahan',
                    'teknisi.nama as nama_teknisi',
                    DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                    DB::raw("DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tgl_selesai"),
                    DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
                    DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
                    'laporan.lap_no_ref'
                )
                    ->where(function ($query) {
                        $query->where('laporanhist.status_laporan', '=', 'Selesai')
                        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                    })
                    ->where('laporan.no_inv_aset', '=', $no_inv_aset)
                    ->where('laporan.status_terakhir', '!=', 'Manager')
                    ->get();
                // dd($data);
            } else if ($tgl_masuk != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                ->join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
                ->orderBy('laporan.tgl_masuk', 'desc')
                ->select(
                    'laporan.id AS id',
                    'no_inv_aset',
                    'tgl_selesai',
                    'waktu_tambahan',
                    'teknisi.nama as nama_teknisi',
                    DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                    DB::raw("DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tgl_selesai"),
                    DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
                    DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
                    'laporan.lap_no_ref'
                )
                    ->where(function ($query) {
                        $query->where('laporanhist.status_laporan', '=', 'Selesai')
                        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                    })
                    ->where('laporan.tgl_masuk', $tgl_masuk_f, $tgl_masuk)
                    ->where('laporan.status_terakhir', '!=', 'Manager')
                    ->get();
                // dd($data); 
            } else if ($tgl_selesai != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                ->join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
                ->orderBy('laporan.tgl_masuk', 'desc')
                ->select(
                    'laporan.id AS id',
                    'no_inv_aset',
                    'tgl_selesai',
                    'waktu_tambahan',
                    'teknisi.nama as nama_teknisi',
                    DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                    DB::raw("DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tgl_selesai"),
                    DB::raw("DATE_FORMAT(tgl_awal_pengerjaan, '%d %M %Y (%H:%i)') AS tgl_awal_pengerjaan"),
                    DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y  (%H:%i)') AS tgl_akhir_pengerjaan"),
                    'laporan.lap_no_ref'
                )
                    ->where(function ($query) {
                        $query->where('laporanhist.status_laporan', '=', 'Selesai')
                        ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                    })
                    ->where('laporan.status_terakhir', '!=', 'Manager')
                    ->get();
                // dd($data);
            }
        }

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
                    DB::raw("DATE_FORMAT(tanggal, '%d %M %Y (%H:%i:%s)') AS tanggal"),
                )
                ->get();
        }

        if ($data->count() == 0) {
            $datas = 'tidak ada';
        } else {
            $datas = 'ada';
        }
        return view('admin.history', compact('data', 'datas', 'filter', 'kat_layanan'));
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
