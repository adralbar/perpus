<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class registController extends Controller
{
    // Tampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.regist');
    }

    // Proses registrasi pengguna
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password harus memiliki minimal 8 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buat pengguna baru
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
