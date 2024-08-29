<?php

namespace App\Http\Controllers;

use App\Models\absensici;
use Illuminate\Support\Facades\DB;
use App\Models\absensico;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class rekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rekap.rekapAbsensi');
    }

    public function getData()
    {
        $data = DB::table('absensici')
            // Join tabel absensici dengan subquery yang menghasilkan check-in pertama pada setiap npk dan tanggal
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                // gabungkan subquery dengan tabel absensici berdasarkan npk, tanggal, dan waktuci
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal')
                    ->on('absensici.waktuci', '=', 'first_checkin.waktuci');
                // menggabungkan tabel sementara first_checkin dengan absensici, output = semua data check-in dari tabel absensici
            })

            // Left join tabel absensici dengan subquery yang menghasilkan check-out terakhir pada hari yang sama
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                // Gabungkan subquery dengan tabel absensici berdasarkan npk dan tanggal
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
                // Menggabungkan tabel absensici dengan waktu checkout pada hari yang sama
            })

            // Left join tabel absensici dengan subquery yang menghasilkan check-out terakhir pada hari berikutnya
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                // Gabungkan subquery dengan tabel absensici berdasarkan npk dan tanggal hari berikutnya (tanggal + 1 hari)
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal')
                    ->whereNotExists(function ($query) {
                        // Pastikan tidak ada check-in berikutnya sebelum waktu check-out terakhir hari berikutnya
                        $query->select(DB::raw(1))
                            ->from('absensici as next_checkin')
                            ->whereRaw('next_checkin.npk = absensici.npk')
                            ->whereRaw('next_checkin.tanggal = DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)')
                            ->whereRaw('next_checkin.waktuci < last_checkout_tomorrow.waktuco');
                    });
                // Menggabungkan tabel absensici dengan waktu checkout pada hari berikutnya, dengan syarat tidak ada check-in sebelum waktu checkout tersebut
            })

            // Memilih kolom yang diperlukan dari hasil join
            ->select(
                'absensici.nama', // Nama 
                'absensici.npk', // NPK
                'absensici.tanggal', // Tanggal checkin
                'first_checkin.waktuci as waktuci', // Waktu check-in pertama
                DB::raw('COALESCE(last_checkout_tomorrow.waktuco, last_checkout_today.waktuco) as waktuco') // Waktu check-out terakhir, dari hari yang sama atau hari berikutnya
            )
            // Mengelompokkan data berdasarkan npk, tanggal, nama, waktu check-in pertama, dan waktu check-out
            ->groupBy('absensici.npk', 'absensici.tanggal', 'absensici.nama', 'first_checkin.waktuci', 'last_checkout_today.waktuco', 'last_checkout_tomorrow.waktuco')
            // Mengurutkan hasil berdasarkan tanggal secara menurun (terbaru di atas)
            ->orderBy('absensici.tanggal', 'desc')
            // Eksekusi query dan dapatkan hasilnya
            ->get();


        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeCheckin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuci' => 'required|date_format:H:i',
        ]);

        absensici::create([
            'nama' => $request->nama,
            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuci' => $request->waktuci,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function storeCheckout(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuco' => 'required|date_format:H:i',
        ]);

        absensico::create([
            'nama' => $request->nama,
            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuco' => $request->waktuco,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }
}
