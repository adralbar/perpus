<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAksesMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @param  int  $role
     * @return mixed
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah pengguna terautentikasi
        if (Auth::check()) {
            // Ambil role_id pengguna saat ini
            $userRole = Auth::user()->role_id;

            // Cek apakah role pengguna ada dalam daftar role yang diizinkan
            if (in_array($userRole, $roles)) {
                return $next($request);
            }
        }

        // Jika tidak ada yang cocok, redirect ke halaman sebelumnya
        return redirect()->to(url()->previous());
    }
}
