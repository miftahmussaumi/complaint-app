<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        if (Auth::guard('pelapor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/home');
        } else if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/it');
        }
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('pelapor')->check()) {
            Auth::guard('pelapor')->logout();
        } else if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        return redirect('/login');
    }

}
