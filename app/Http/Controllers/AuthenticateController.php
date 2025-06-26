<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function tampil_login()
    {
        return view('Authenticate.Login');
    }

    public function proses_login(Request $request)
    {
        // Validasi inputnya
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // if (Auth::user()->jabatan_id == 1 && Auth::user()->department_id == 1) {
            return redirect()->intended('/dashboard-admin');
            // } else {
            //     return redirect()->intended('/dashboard-admin');
            // }
        }

        // Jika gagal login
        return redirect('/login')->with('error', 'Email atau password salah.');
    }

    public function proses_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
