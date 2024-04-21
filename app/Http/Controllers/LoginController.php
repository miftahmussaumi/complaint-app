<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelapor;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('welcome2');
    }

    public function postlogin(Request $request)
    {
        // dd($request->all());
        // if (Auth::guard('pelapor')->attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect('/dashboard-user');
        // } else if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect('/it');
        // } else if (Auth::guard('pengawas')->attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect('/list-akun');
        // }
        $credentials = $request->only('email', 'password');

        $pelapor = Pelapor::where('email', $request->email)->first();

        if ($pelapor && $pelapor->status == 0) {
            return redirect()->back()->with('error', 'Akun Anda belum disetujui.');
        }

        if (Auth::guard('pelapor')->attempt($credentials)) {
            return redirect('/profile-pelapor');
        } elseif (Auth::guard('teknisi')->attempt($credentials)) {
            return redirect('/profile-teknisi');
        } elseif (Auth::guard('pengawas')->attempt($credentials)) {
            return redirect('/profile-pengawas');
        } elseif (Auth::guard('admin')->attempt($credentials)) {
            return redirect('/laporan-admin');
        }
        return redirect('/');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('pelapor')->check()) {
            Auth::guard('pelapor')->logout();
        } else if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else if (Auth::guard('pengawas')->check()) {
            Auth::guard('pengawas')->logout();
        } else if (Auth::guard('teknisi')->check()) {
            Auth::guard('teknisi')->logout();
        }

        return redirect('/');
    }

}
