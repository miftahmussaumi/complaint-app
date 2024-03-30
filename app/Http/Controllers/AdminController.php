<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengawas;
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
        $akun = DB::table('admin')->get();
        return view('pelapor.complaint', compact('akun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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

            $old_ttd = $request->ttd_old;
            unlink(storage_path('app/public/img/admin/' . $old_ttd));
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
    public function store(Request $request)
    {
        //
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
