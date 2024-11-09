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
        Session::flash('username', $request->username);
        $credentials = $request->validate([
            'npk' => 'required|string',
            'password' => 'required|string',
        ], [
            'npk.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleId = $user->role_id;

            $request->session()->put('role_id', $roleId);

            if (in_array($roleId, [2, 9])) {
                return redirect()->intended('/shift');
            } elseif (in_array($roleId, [1, 6])) {
                return redirect()->intended('/rekap');
            }


            return redirect()->intended('/'); // atau halaman default lain
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
