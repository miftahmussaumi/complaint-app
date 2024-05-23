<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelapor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; 

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function postlogin(Request $request)
    {
        // dd($request->all());
        if (Auth::guard('pelapor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('pelapor')->user();
            if ($user->status == 1) {
                return redirect('/profile-pelapor');
            } else {
                $msg = 'Akun ada belum disetujui Manajer';
                Session::flash('warning', $msg); 
                Auth::guard('pelapor')->logout();
                return redirect('/');
            }
            // return redirect('/profile-pelapor');
        } else if (Auth::guard('teknisi')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/profile-teknisi');
        } else if (Auth::guard('pengawas')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/profile-pengawas');
        } else if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/dashboard-admin');
        }
        // $credentials = $request->only('email', 'password');

        // if (Auth::guard('pelapor')->attempt($credentials)) {
        //     return redirect('/profile-pelapor');
        // } elseif (Auth::guard('teknisi')->attempt($credentials)) {
        //     return redirect('/profile-teknisi');
        // } elseif (Auth::guard('pengawas')->attempt($credentials)) {
        //     return redirect('/profile-pengawas');
        // } elseif (Auth::guard('admin')->attempt($credentials)) {
        //     return redirect('/dashboard-admin');
        // }
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
