<?php

namespace App\Http\Controllers;

use App\Models\Readlist;
use Illuminate\Http\Request;
use App\Models\kategoriModel;
use App\Models\daftarreadlist;
use App\Models\daftarBukuModel;
use Yajra\DataTables\DataTables;
use App\Models\daftarPinjamModel;

class katalogController extends Controller
{
    public function index(Request $request)
    {
        $kategori = kategoriModel::all();

        return view('katalog.katalog', compact('kategori'));
    }
    public function tambahKeReadlist(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:daftarbuku,id',
        ]);

        // Ambil informasi buku berdasarkan ID
        $buku = daftarBukuModel::find($request->buku_id);
        $user = auth()->user();
        // Simpan ke readlist (asumsi ada model Readlist yang berhubungan)
        daftarreadlist::create([
            'buku_id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'penerbit' => $buku->penerbit,
            'tanggal' => $buku->tanggal,
            'nomorisbn' => $buku->nomorisbn,
            'bahasa' => $buku->bahasa,
            'kategori' => $buku->kategori,
            'ringkasan' => $buku->ringkasan,
            'foto' => $buku->foto,
            'email' => $user->email, // Menambahkan email dari user yang sedang login
            'nama' => $user->nama,
        ]);

        return response()->json(['message' => 'Buku berhasil ditambahkan ke readlist']);
    }

    public function tambahKePinjam(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:daftarbuku,id',
        ]);

        // Ambil informasi buku berdasarkan ID
        $buku = daftarBukuModel::find($request->buku_id);
        $user = auth()->user();
        // Simpan ke readlist (asumsi ada model Readlist yang berhubungan)
        daftarPinjamModel::create([
            'buku_id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'penerbit' => $buku->penerbit,
            'tanggal' => $buku->tanggal,
            'nomorisbn' => $buku->nomorisbn,
            'bahasa' => $buku->bahasa,
            'kategori' => $buku->kategori,
            'ringkasan' => $buku->ringkasan,
            'foto' => $buku->foto,
            'email' => $user->email, // Menambahkan email dari user yang sedang login
            'nama' => $user->nama,
            'status' => '0',
        ]);

        return response()->json(['message' => 'Buku berhasil ditambahkan ke readlist']);
    }

    public function getBuku(Request $request)
    {
        $query = daftarBukuModel::query();

        // Urutkan berdasarkan created_at (paling baru di atas)
        $query->orderBy('created_at', 'desc');

        // Filter berdasarkan kategori jika ada
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('kategori', $request->kategori);
        }

        $data = $query->paginate(6); // Mengambil 6 buku per halaman

        // Menambahkan URL untuk foto
        foreach ($data as $buku) {
            $buku->foto = asset('storage/' . ltrim($buku->foto, '/'));
        }

        return response()->json([
            'data' => $data->items(),  // Mengembalikan data buku pada halaman ini
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
        ]);
    }


    public function getBukuDashboard(Request $request)
    {

        $data = daftarBukuModel::orderBy('created_at', 'desc')->paginate(10);
        // Menambahkan URL untuk foto
        foreach ($data as $buku) {
            $buku->foto = asset('storage/' . str_replace('/uploads', '/uploads', $buku->foto));
        }

        return response()->json([
            'data' => $data->items(),  // Mengembalikan data buku pada halaman ini
        ]);
    }
    public function showDetail($id)
    {
        $buku = daftarBukuModel::find($id);

        if (!$buku) {
            return redirect()->route('katalog.index')->with('error', 'Buku tidak ditemukan');
        }

        $buku->foto = asset('storage/' . str_replace('/uploads', '/uploads', $buku->foto));

        return view('katalog.detail', compact('buku'));
    }
    public function showDetail2($id)
    {
        // Mencari buku berdasarkan ID
        $buku = daftarBukuModel::find($id);

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
            'ringkasan' => 'required|string|max:5000', // Ubah max menjadi lebih panjang
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validasi sebagai gambar max 2MB
        ]);

        // Upload Foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads/buku', 'public'); // Simpan di storage/app/public/uploads/buku
        } else {
            return response()->json(['error' => 'Gagal mengunggah foto!'], 400);
        }

        daftarBukuModel::create([
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
        $buku = daftarBukuModel::find($id);
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


    public function checkReadlist(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:daftarbuku,id',
        ]);

        $user = auth()->user();

        // Cek apakah buku sudah ada di readlist
        $exists = daftarreadlist::where('buku_id', $request->buku_id)
            ->where('email', $user->email)
            ->exists();

        return response()->json(['exists' => $exists]);
    }


    // Fungsi untuk menangani penghapusan buku
    public function destroy($id)
    {
        // Temukan buku berdasarkan id
        $buku = daftarBukuModel::findOrFail($id);

        // Hapus buku tersebut
        $buku->delete();

        // Redirect kembali ke halaman daftar buku dengan pesan sukses
        return redirect()->route('katalog.index')->with('success', 'Buku berhasil dihapus!');
    }
}
