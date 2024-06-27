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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


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
        ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
            $join->on('laporan.id', '=', 'latest_laporanhist.id_laporan');
        })
        ->leftJoin('laporanhist', function ($join) {
            $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
        })
        ->select('laporanhist.status_laporan as status_terakhir')
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
        ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
            $join->on('laporan.id', '=', 'latest_laporanhist.id_laporan');
        })
        ->leftJoin('laporanhist', function ($join) {
            $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
        })
        ->leftJoin('teknisi','teknisi.id','=','laporan.id_teknisi')
        ->leftJoin('pelapor','pelapor.id','=','laporan.id_pelapor')
        ->select(
            'no_inv_aset',
            'waktu_tambahan',
            'laporanhist.status_laporan as status_terakhir',
            'tgl_akhir_pengerjaan AS deadline',
            'laporan.id AS id','id_teknisi','teknisi.nama as nama_teknisi',
            'laporan.tgl_masuk','pelapor.nama as nama_pelapor',
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk_f"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
        )
        ->orderBy('laporan.tgl_masuk','desc')
        ->whereNotIn('laporanhist.status_laporan', ['Dibatalkan', 'Selesai'])
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
        ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
            $join->on('laporan.id', '=', 'latest_laporanhist.id_laporan');
        })
        ->leftJoin('laporanhist', function ($join) {
            $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
        })
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
                'laporanhist.status_laporan as status_terakhir','laporan.id as id','pelapor.nama as nama_pelapor', 'pelapor.jabatan as jabatan_pelapor'
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
        $filter         = $request->filter;
        $tgl_masuk      = $request->tgl_masuk;
        $tgl_masuk_f    = $request->tgl_masuk_f;
        $tgl_selesai    = $request->tgl_selesai;
        $tgl_selesai_f  = $request->tgl_selesai_f;
        $no_inv_aset    = $request->no_inv_aset;
        $kat_layanan    = $request->kat_layanan;

        if ($filter == null) {
            $data = DB::table('laporan')
            ->leftJoin(DB::raw('(SELECT id_laporan, MAX(tanggal) AS tanggal FROM laporanhist GROUP BY id_laporan) AS latest_laporanhist'), function ($join) {
                $join->on('laporan.id', '=', 'latest_laporanhist.id_laporan');
            })
            ->leftJoin('laporanhist', function ($join) {
                $join->on('laporan.id', '=', 'laporanhist.id_laporan')
                    ->on('laporanhist.tanggal', '=', 'latest_laporanhist.tanggal');
            })
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
                'laporan.lap_no_ref', 'laporanhist.status_laporan as status_terakhir'
            )
                ->orderBy('laporanhist.tanggal', 'desc')
                ->where(function ($query) {
                    $query->where('laporanhist.status_laporan', '=', 'Selesai')
                    ->orWhere('laporanhist.status_laporan', '=', 'Dibatalkan');
                })
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
    public function akun()
    {
        $pelapor = DB::table('pelapor as p')
        ->leftJoin('laporan as l', 'p.id', '=', 'l.id_pelapor')
        ->select(
            'p.id',
            'p.nama',
            'p.nipp',
            'p.email',
            'p.password',
            'p.jabatan',
            'p.divisi',
            'p.telepon',
            'p.status',
            DB::raw('COUNT(l.id) AS jumlah_laporan')
        )
            ->where('status', '=', 1)
            ->groupBy(
                'p.id',
                'p.nama',
                'p.nipp',
                'p.email',
                'p.password',
                'p.jabatan',
                'p.divisi',
                'p.telepon',
                'p.status',
            )
            // ->orderByDesc('p.created_at')
            ->get();

        $acc    = DB::table('pelapor')->where('status', '=', 0)->get();
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
            ->groupBy(
                't.id',
                't.nama',
                't.nipp',
                't.email',
                't.jabatan',
            )->get();

        // dd($acc);

        return view('admin.akun-user', compact('pelapor', 'it', 'cacc', 'acc'));
    }

    public function listaccakun()
    {
        $pelapor    = DB::table('pelapor')->where('status', '=', 0)->get();
        $teknisi    = DB::table('teknisi')->where('status', '=', 0)->get();
        $pengawas   = DB::table('pengawas')->where('status', '=', 0)->get();


        // $action         = $request->action;
        // $id_admin_tj    = $request->id_admin_tj;

        // Session::flash('success');

        return view('admin.acc-akun',compact('pelapor','teknisi','pengawas'));
    }

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

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function pelapor($id)
    {
        $pelapor = DB::table('pelapor as p')
        ->select(
            'p.id',
            'p.nama',
            'p.nipp',
            'p.email',
            'p.password',
            'p.jabatan',
            'p.divisi',
            'p.telepon',
            'p.status',
        )
        ->where('p.id', '=', $id)
        ->get();

        // dd($pelapor);
        return view('admin.edit-pelapor', compact('pelapor'));
    }
    
    public function editpelapor(Request $request, $id)
    {
        $update = DB::table('pelapor')
        ->where('id', '=', $id)
        ->update([
            'nama'      => $request->nama,
            'nipp'      => $request->nipp,
            'email'     => $request->email,
            'jabatan'   => $request->jabatan,
            'divisi'    => $request->divisi,
            'telepon'   => $request->telepon,
            'password'  => Hash::make($request->new_password)
        ]);

        Session::flash('success'); 

        // dd($request->all());
        return back();
    }

    public function editteknisi(Request $request, $id)
    {
        $update = DB::table('teknisi')
        ->where('id', '=', $id)
            ->update([
                'nama'      => $request->nama,
                'nipp'      => $request->nipp,
                'email'     => $request->email,
                'jabatan'   => $request->jabatan,
            ]);
        Session::flash('success');

        // dd($request->all());
        return back();
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
