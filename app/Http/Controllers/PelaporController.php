<?php

namespace App\Http\Controllers;

use App\Models\Pelapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PelaporController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $pass1 = $request->password;
        $pass = bcrypt($request->password);

        Pelapor::create([
            'nama'      => $request->nama,
            'nipp'      => $request->nipp,
            'email'     => $request->email,
            'password'  => $pass,
            'jabatan'   => $request->jabatan,
            'divisi'    => $request->divisi,
            'telepon'   => $request->telepon,
            'status'    => 0
        ]);

        // dd($pass1, $pass);
        return view('welcome2');
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

            // $old_ttd = $request->ttd_old;
            // unlink(storage_path('app/public/img/pelapor/' . $old_ttd));
        }

        // dd($ttd, $filettd);
        return redirect('profile-pelapor');
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
