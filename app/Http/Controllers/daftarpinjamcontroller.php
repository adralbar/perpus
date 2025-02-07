<?php

namespace App\Http\Controllers;

use App\Models\daftarPinjamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class daftarpinjamcontroller extends Controller
{
    public function index(Request $request)
    {
        return view('daftarpinjam.daftarpinjam');
    }
    public function getBuku(Request $request)
    {
        $userEmail = Auth::user()->email;
        $userRole = Auth::user()->role_id; // Mendapatkan role_id pengguna yang sedang login


        if ($userRole == 1) {
            $data = daftarPinjamModel::paginate(6);
        } else {
            // Jika role bukan 1, tampilkan buku yang sesuai dengan email pengguna
            $data = daftarPinjamModel::where('email', $userEmail)->paginate(6);
        }

        // Menambahkan URL untuk foto
        foreach ($data as $buku) {
            $buku->foto = asset('storage/' . str_replace('/uploads', '/uploads', $buku->foto));

            if ($buku->status == 0) {
                $buku->can_change_status = true; // Menambahkan properti can_change_status jika status = 0
            } else {
                $buku->can_change_status = false; // Menambahkan properti can_change_status jika status = 1
            }
        }

        return response()->json([
            'data' => $data->items(),  // Mengembalikan data buku yang sesuai dengan email pengguna
        ]);
    }
    public function updateStatus($id, Request $request)
    {
        $buku = daftarPinjamModel::findOrFail($id);
        $buku->status = $request->status;
        $buku->save();

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }
}
