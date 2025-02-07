<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\daftarreadlist;
use Illuminate\Support\Facades\Auth;

class readlist extends Controller
{
    public function index(Request $request)
    {
        return view('readlist.readlist');
    }
    public function getBuku(Request $request)
    {
        // Ambil email user yang sedang login
        $userEmail = Auth::user()->email;

        // Ambil data buku yang memiliki email yang sesuai dengan email pengguna yang sedang login
        $data = daftarreadlist::where('email', $userEmail)->paginate(6);

        // Menambahkan URL untuk foto
        foreach ($data as $buku) {
            $buku->foto = asset('storage/' . str_replace('/uploads', '/uploads', $buku->foto));
        }

        return response()->json([
            'data' => $data->items(),  // Mengembalikan data buku yang sesuai dengan email pengguna
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
        ]);
    }
    public function showDetail($id)
    {
        $buku = daftarreadlist::find($id);

        if (!$buku) {
            return redirect()->route('katalog.index')->with('error', 'Buku tidak ditemukan');
        }

        $buku->foto = asset('storage/' . str_replace('/uploads', '/uploads', $buku->foto));

        return view('readlist.readlist', compact('buku'));
    }


    public function showDetail2($id)
    {
        // Mencari buku berdasarkan ID
        $buku = daftarreadlist::find($id);

        // Jika buku tidak ditemukan
        if (!$buku) {
            return response()->json(['error' => 'Buku tidak ditemukan'], 404);
        }

        // Menyesuaikan path foto
        $buku->foto = $buku->foto ? asset('storage/' . $buku->foto) : null;

        // Mengembalikan data buku dalam format JSON
        return response()->json($buku);
    }

    public function storeDaftarBuku(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:50',
            'penulis' => 'required|string|max:50',
            'penerbit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'nomorisbn' => 'required|string|max:50',
            'bahasa' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'ringkasan' => 'required|string|max:500', // Ubah max menjadi lebih panjang
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validasi sebagai gambar max 2MB
        ]);

        // Upload Foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads/buku', 'public'); // Simpan di storage/app/public/uploads/buku
        } else {
            return response()->json(['error' => 'Gagal mengunggah foto!'], 400);
        }

        daftarreadlist::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tanggal' => $request->tanggal,
            'nomorisbn' => $request->nomorisbn,
            'bahasa' => $request->bahasa,
            'kategori' => $request->kategori,
            'ringkasan' => $request->ringkasan,
            'foto' => $fotoPath, // Simpan path foto
        ]);

        return response()->json(['success' => 'Daftar Buku berhasil ditambahkan!']);
    }
    public function updateDaftarBuku(Request $request, $id)
    {
        // Cek apakah buku dengan ID yang diberikan ada
        $buku = daftarreadlist::find($id);
        if (!$buku) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        try {
            // Validasi input
            $validatedData = $request->validate([
                'judul' => 'required|string',
                'penulis' => 'required|string',
                'penerbit' => 'required|string',
                'tanggal' => 'required|date',
                'nomorisbn' => 'required|string',
                'bahasa' => 'required|string',
                'kategori' => 'required|string',
                'ringkasan' => 'required|string',
                'foto' => 'nullable|image|max:2048',
            ]);

            // Menangani file foto jika ada
            if ($request->hasFile('foto')) {
                // Simpan file dan ambil path-nya
                $fotoPath = $request->file('foto')->store('buku', 'public');
                $validatedData['foto'] = $fotoPath;
            }

            // Update data buku
            $buku->update($validatedData);

            // Mengembalikan response sukses
            return response()->json(['success' => true, 'message' => 'Buku berhasil diperbarui']);
        } catch (\Exception $e) {
            // Menangani error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
