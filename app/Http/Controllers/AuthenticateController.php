<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{
    public function tampil_login()
    {
        return view('Authenticate.Login');
    }

    public function proses_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // if (Auth::user()->jabatan_id == 1 && Auth::user()->department_id == 1) {
            return redirect()->intended('/dashboard');
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

    public function ChangePassword(Request $request)
    {
        $validatePassword = $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
        ]);

        $user = Auth::user();

        if (isset($user)) {
            $user->update([
                'password' => Hash::make($validatePassword['password']),
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'Password berhasil diubah, silahkan log in kembali.');
        } else {
            return redirect()->back()->with('error', 'Password gagal diubah.');
        }
    }
}
