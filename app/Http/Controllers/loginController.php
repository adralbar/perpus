<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class loginController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        Session::flash('username', $request->username);
        $credentials = $request->validate([
            'npk' => 'required|string',
            'password' => 'required|string',
        ], [
            'npk.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleId = $user->role_id;

            $request->session()->put('role_id', $roleId);

            // Redirect berdasarkan role_id
            if ($roleId == 2) {
                return redirect()->intended('/shift');
            }
            return redirect()->intended('/');
        } else {
            return back()->withErrors([
                'login' => 'Username atau password salah.',
            ]);
        }
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // 
    }
}
