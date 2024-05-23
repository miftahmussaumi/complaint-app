<?php

namespace App\Http\Controllers;

use App\Models\Pelapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DetLaporan;
use App\Models\Laporan;
use App\Models\Laporanakhir;
use App\Models\Laporanhist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session; 

class PelaporController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dtLap = DB::table('laporan')
        ->select(
            'no_inv_aset',
            'waktu_tambahan',
            'status_terakhir',
            'id',
            'laporan.tgl_akhir_pengerjaan AS deadline',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
        )
            ->whereNotIn('status_terakhir', ['Selesai', 'Dibatalkan', 'Manager'])
            ->where('id_pelapor', '=', Auth::guard('pelapor')->user()->id)
            ->orderBy('tgl_masuk')
            ->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'

        return view('pelapor.complaint', compact('dtLap')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $laporan = DB::table('laporan')
        ->leftjoin('teknisi','teknisi.id','=','laporan.id_teknisi')
        ->where('laporan.id','=',$id)
        ->select(
            DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%d %M %Y') AS tgl_selesai"),
            DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d %M %Y, %H:%i WIB') AS tgl_awal_pengerjaan"),
            DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
            'no_inv_aset','waktu_tambahan','id_teknisi','teknisi.nama',
            'waktu_tambahan_peng','status_terakhir','laporan.id as idlap',
            'laporan.tgl_akhir_pengerjaan AS deadline','laporan.id AS idlap'
        )
        ->first();

        $detlaporan = DB::table('detlaporan')
        ->where('id_laporan','=',$id)->get();

        $hislap = DB::table('laporanhist')
        ->join('laporan','laporan.id','=','laporanhist.id_laporan')
        ->orderBy('tanggal', 'desc')
        ->first();

        // dd($laporan);
        return view('pelapor.comp-detail', compact('laporan','detlaporan','hislap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pass = bcrypt($request->password);
        $ttd = $request->ttd;

        $existingUser = Pelapor::where('email', $request->email)->first();
        if ($existingUser) {
            return back()->withInput()->withErrors(['error' => 'Gunakan Email yang Lain']);
        }

        $pelapor = Pelapor::create([
            'nama'      => $request->nama,
            'nipp'      => $request->nipp,
            'email'     => $request->email,
            'password'  => $pass,
            'jabatan'   => $request->jabatan,
            'divisi'    => $request->divisi,
            'telepon'   => $request->telepon,
            'status'    => 0
        ]);

        $id_pelapor = $pelapor->id; 

        $nama_file_ttd = $id_pelapor . "_" . time() . "_" . $ttd->getClientOriginalName();
        $ttd->move(storage_path() . '/app/public/img/pelapor', $nama_file_ttd);

        DB::table('pelapor')
            ->where('id', $id_pelapor)
            ->update([
                'ttd'  => $nama_file_ttd
            ]);

        $msg = 'Akun Berhasil Dibuat';
        Session::flash('success', $msg); 

        // dd($nama_file_ttd);
        return view('login');
    }

    public function profile()
    {
        $dt = DB::table('pelapor')
        ->where('id', '=', Auth::guard('pelapor')->user()->id)
        ->first();

        // dd($dt);

        return view('pelapor.profile', compact('dt'));
    }

    public function ttd(Request $request)
    {
        $ttd = $request->ttd;

        $getttd = DB::table('pelapor')
        ->select('ttd')
        ->where('id', Auth::guard('pelapor')->user()->id)
            ->first();

        $cekttd = $getttd->ttd;

        if ($cekttd == '') {
            $nama_file_ttd = Auth::guard('pelapor')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/pelapor', $nama_file_ttd);

            DB::table('pelapor')
            ->where('id', Auth::guard('pelapor')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);
        } else if ($cekttd != '') {
            $nama_file_ttd = Auth::guard('pelapor')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/pelapor', $nama_file_ttd);

            DB::table('pelapor')
            ->where('id', Auth::guard('pelapor')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);

            $old_ttd = $request->ttd_old;
            // unlink(storage_path('app/public/img/pelapor/' . $old_ttd));
        }

        // dd($ttd, $filettd);
        return redirect('profile-pelapor');
    }

    public function foto(Request $request)
    {
        $profile = $request->profile;

        $getprofile = DB::table('pelapor')
        ->select('profile')
            ->where('id', Auth::guard('pelapor')->user()->id)
            ->first();

        $cekprofile = $getprofile->profile;

        if ($cekprofile == '') {
            $nama_file_profile = Auth::guard('pelapor')->user()->id . "_" . time() . "_" . $profile->getClientOriginalName();
            $profile->move(storage_path() . '/app/public/img/pp_pelapor/', $nama_file_profile);

            DB::table('pelapor')
            ->where('id', Auth::guard('pelapor')->user()->id)
                ->update([
                    'profile'  => $nama_file_profile
                ]);
        } else if ($cekprofile != '') {
            $nama_file_profile = Auth::guard('pelapor')->user()->id . "_" . time() . "_" . $profile->getClientOriginalName();
            $profile->move(storage_path() . '/app/public/img/pp_pelapor/', $nama_file_profile);

            DB::table('pelapor')
            ->where('id', Auth::guard('pelapor')->user()->id)
                ->update([
                    'profile'  => $nama_file_profile
                ]);

            $old_profile = $request->profile_old;
            // dd($old_profile);
            // unlink(storage_path('app/public/img/pp_pelapor/' . $old_profile));
        }


        // dd($profile, $fileprofile);
        return redirect('profile-pelapor');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acclap(Request $request, $idlap)
    {
        $action = $request->action;
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        if($action == 'accept') {
            DB::table('laporan')
            ->where('id', $idlap)
            ->update([
                'status_terakhir'   => 'Selesai',
                'tgl_selesai'       => $tgl_masuk
            ]);

            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Selesai',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Laporan telah diselesaikan dengan baik'
            ]);
        } else if ($action == 'reject') {
            DB::table('laporan')
                ->where('id', $idlap)
                ->update([
                    'status_terakhir'   => 'Dibatalkan',
                    'tgl_selesai'       => $tgl_masuk
                ]);

            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Dibatalkan',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => $request->keterangan
            ]);
        }

        return redirect('comp');
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
    public function tambahwaktu(Request $request, $idlap)
    {
        $action     = $request->input('action');
        $tgl_masuk  = Carbon::now()->format('Y-m-d H:i:s');

        if ($action === 'accept') {
            $lap = DB::table('laporan')->where('id', $idlap)->first();
            $waktu_tambahan = $lap->waktu_tambahan;
            $waktu_tambahan = $waktu_tambahan + $request->waktu_tambahan_peng;

            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Diproses',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Penambahan waktu diterima'
            ]);

            DB::table('laporan')
                ->where('id', $idlap)
                ->update([
                    'waktu_tambahan_peng'   => DB::raw('NULL'),
                    'waktu_tambahan'        => $waktu_tambahan,
                    'tgl_selesai'           => $tgl_masuk,
                    'status_terakhir'       => 'Diproses'
                ]);
        } elseif ($action === 'reject') {
            Laporanhist::create([
                'id_laporan'        => $idlap,
                'status_laporan'    => 'Dibatalkan',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => $request->keterangan
            ]);

            DB::table('laporan')
            ->where('id', $idlap)
                ->update([
                    'waktu_tambahan_peng'  => DB::raw('NULL'),
                    'tgl_selesai'           => $tgl_masuk,
                    'status_terakhir'       => 'Dibatalkan'
                ]);
        } 

        // dd($action, $request->waktu_tambahan_peng);

        return redirect('comp');
    }

    /**
     * Remove the specified resource from storage.
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
            ->join('teknisi','teknisi.id','=','laporan.id_teknisi')
            ->select(
                'laporan.id AS id',
                'no_inv_aset',
                'tgl_selesai',
                'waktu_tambahan','teknisi.nama as nama_teknisi',
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
                ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
                ->get();
        } else {
            if ($kat_layanan != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                ->join('detlaporan', 'detlaporan.id_laporan', '=', 'laporan.id')
                -> join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
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
                    ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
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
                    ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
                    ->get();
                // dd($data);
            } else if ($tgl_masuk != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                -> join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
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
                    ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
                    ->get();
                // dd($data); 
            } else if ($tgl_selesai != null) {
                $data = DB::table('laporan')
                ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
                -> join('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
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
                    ->where('laporan.id_pelapor', '=', Auth::guard('pelapor')->user()->id)
                    ->get();
                // dd($data);
            }
        }

        foreach ($data as $laporan) {
            $laporan->history = DB::table('laporanhist')
            ->where('id_laporan', $laporan->id)
            ->where('laporanhist.status_laporan', '!=', 'Manager') 
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
        return view('pelapor.history-u', compact('data', 'datas', 'filter', 'kat_layanan'));
    }
}
