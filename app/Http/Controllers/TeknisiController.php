<?php

namespace App\Http\Controllers;
use App\Models\Laporan;
use App\Models\Laporanakhir;
use App\Models\Laporanhist;
use App\Models\DetLaporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TeknisiController extends Controller
{
    public function profile()
    {
        $dt = DB::table('teknisi')
        ->where('id', '=', Auth::guard('teknisi')->user()->id)
            ->first();

        // dd($dt);

        return view('teknisi.profile', compact('dt'));
    }

    public function ttd(Request $request)
    {
        $ttd = $request->ttd;

        $getttd = DB::table('teknisi')
        ->select('ttd')
        ->where('id', Auth::guard('teknisi')->user()->id)
            ->first();

        $cekttd = $getttd->ttd;

        if ($cekttd == '') {
            $nama_file_ttd = Auth::guard('teknisi')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/teknisi', $nama_file_ttd);

            DB::table('teknisi')
            ->where('id', Auth::guard('teknisi')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);
        } else if ($cekttd != '') {
            $nama_file_ttd = Auth::guard('teknisi')->user()->id . "_" . time() . "_" . $ttd->getClientOriginalName();
            $ttd->move(storage_path() . '/app/public/img/teknisi', $nama_file_ttd);

            DB::table('teknisi')
            ->where('id', Auth::guard('teknisi')->user()->id)
                ->update([
                    'ttd'  => $nama_file_ttd
                ]);

            // $old_ttd = $request->ttd_old;
            // unlink(storage_path('app/public/img/teknisi/' . $old_ttd));
        }

        // dd($ttd, $filettd);
        return redirect('profile-teknisi');
    }

    public function index()
    {
        $dtLap = DB::table('laporan')
        ->select(
            DB::raw("DATE_FORMAT(tgl_masuk, '%d %M %Y') AS tgl_masuk"),
            DB::raw("DATE_FORMAT(tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
            'no_inv_aset',
            'waktu_tambahan',
            'status_terakhir',
            'id','id_teknisi',
            'laporan.tgl_akhir_pengerjaan AS deadline',
        )
            ->whereNotIn('status_terakhir', ['Selesai', 'Dibatalkan','Manager'])
            ->where('id_teknisi', '=', Auth::guard('teknisi')->user()->id)
            ->orderBy('tgl_masuk')
            ->get();

        // FORMAT TANGGAL '%d/%m/%Y %H:%i'

        return view('teknisi.comp', compact('dtLap'));
    }

    public function proses(Request $request, $id)
    {
        $action = $request->action;
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        if($action == 'process'){
            Laporanhist::create([
                'id_laporan'        => $id,
                'status_laporan'    => 'Diproses',
                'tanggal'           => $tgl_masuk,
            ]);
            DB::table('laporan')
                ->where('id', $id)
                ->update([
                    'status_terakhir' => 'Diproses'
            ]);
        } else if ($action == 'reject') {
            Laporanhist::create([
                'id_laporan'        => $id,
                'status_laporan'    => 'Dibatalkan',
                'tanggal'           => $tgl_masuk,
                'keterangan'        => 'Permintaan laporan dibatalkan oleh Teknisi'
            ]);
            DB::table('laporan')
                ->where('id', $id)
                ->update([
                    'status_terakhir' => 'Dibatalkan'
                ]);
        }

        return redirect()->back();
    }

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
                'waktu_tambahan_peng'   => $waktu_tambahan,
                'status_terakhir'       => 'reqAddTime'
            ]);

        // dd($waktu_tambahan, $keterangan);
        return redirect()->back();
    }

    public function detail($id)
    {
        $laporan = DB::table('laporan')
        ->leftjoin('teknisi', 'teknisi.id', '=', 'laporan.id_teknisi')
        ->where('laporan.id', '=', $id)
            ->select(
                'no_inv_aset',
                'waktu_tambahan','waktu_tambahan_peng',
                'id_teknisi','laporan.id AS id',
                'teknisi.nama','status_terakhir',
                'laporan.tgl_akhir_pengerjaan AS deadline',
                DB::raw("DATE_FORMAT(laporan.tgl_masuk, '%d %M %Y') AS tgl_masuk"),
                DB::raw("DATE_FORMAT(laporan.tgl_selesai, '%d %M %Y') AS tgl_selesai"),
                DB::raw("DATE_FORMAT(laporan.tgl_awal_pengerjaan, '%d %M %Y, %H:%i WIB') AS tgl_awal_pengerjaan"),
                DB::raw("DATE_FORMAT(laporan.tgl_akhir_pengerjaan, '%d %M %Y,  %H:%i WIB') AS tgl_akhir_pengerjaan"),
            )
            ->first();

        $detlaporan = DB::table('detlaporan')
        ->leftJoin('laporan','laporan.id','=','detlaporan.id_laporan')
        ->where('id_laporan', '=', $id)->get();

        $count = DetLaporan::where('id_laporan', $id)
        ->whereNull('det_pekerjaan')
        ->whereNull('ket_pekerjaan')
        ->count();

        // dd($detlaporan);

        return view('teknisi.comp-detail-it', compact('laporan', 'detlaporan','count'));
        
    }

    public function pekerjaanIT(Request $request, $id)
    {
        $det_pekerjaan = $request->det_pekerjaan;
        $ket_pekerjaan = $request->ket_pekerjaan;

        DB::table('detlaporan')
            ->where('id', $id)
            ->update([
                'det_pekerjaan' => $det_pekerjaan,
                'ket_pekerjaan' => $ket_pekerjaan,
            ]);

        return redirect()->back();
    }

    public function selesai(Request $request, $id)
    {
        $tgl_masuk = Carbon::now()->format('Y-m-d H:i:s');

        Laporanhist::create([
            'id_laporan'        => $id,
            'status_laporan'    => 'CheckedU',
            'tanggal'           => $tgl_masuk,
            'keterangan'        => 'Pengecekan hasil penyelesaian laporan oleh Use/rPelapor'
        ]);

        DB::table('laporan')
            ->where('id', $id)
            ->update([
                'status_terakhir' => 'CheckedU'
            ]);
        
        return redirect('it');
    }

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
                ->where('laporan.id_teknisi', '=', Auth::guard('teknisi')->user()->id)
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
                    ->where('laporan.id_teknisi', '=', Auth::guard('teknisi')->user()->id)
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
                    ->where('laporan.id_teknisi', '=', Auth::guard('teknisi')->user()->id)
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
                    ->where('laporan.id_teknisi', '=', Auth::guard('teknisi')->user()->id)
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
                    ->where('laporan.id_teknisi', '=', Auth::guard('teknisi')->user()->id)
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
        return view('teknisi.history-it', compact('data', 'datas', 'filter', 'kat_layanan'));
    }

    public function getNoInventarisOptionsIT()
    {
        $options = '';

        $noInventaris = DB::table('laporan')
        ->join('laporanhist', 'laporanhist.id_laporan', '=', 'laporan.id')
        ->where('laporanhist.status_laporan', ['Selesai', 'Dibatalkan'])
        ->where('laporan.id_pelapor', '=', Auth::guard('teknisi')->user()->id)
            ->get();

        foreach ($noInventaris as $noInv) {
            $options .= '<option name=""no_inv_aset" value="' . $noInv->no_inv_aset . '">' . $noInv->no_inv_aset . '</option>';
        }

        return response()->json(['options' => $options]);
    }
}
