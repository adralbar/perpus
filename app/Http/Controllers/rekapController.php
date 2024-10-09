<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use Illuminate\Http\Request;
use App\Models\SectionModel;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class rekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rekap.rekapAbsensi');
    }

    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Ambil data check-in dengan join ke tabel kategorishift untuk mendapatkan shift1
        $checkinData = DB::table('absensici')
            ->join('users', 'absensici.npk', '=', 'users.npk')
            ->leftJoin('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->on('absensici.tanggal', '=', 'kategorishift.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensici.tanggal',
                DB::raw('MIN(absensici.waktuci) as waktuci'),
                'kategorishift.shift1' // Mengambil shift1 dari tabel kategorishift
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensici.tanggal',
                'kategorishift.shift1'
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkinData->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $checkinResults = $checkinData->get();

        // Ambil data check-out dengan join ke tabel kategorishift untuk mendapatkan shift1
        $checkoutData = DB::table('absensico')
            ->join('users', 'absensico.npk', '=', 'users.npk')
            ->leftJoin('kategorishift', function ($join) {
                $join->on('absensico.npk', '=', 'kategorishift.npk')
                    ->on('absensico.tanggal', '=', 'kategorishift.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensico.tanggal',
                DB::raw('MAX(absensico.waktuco) as waktuco'),
                'kategorishift.shift1' // Mengambil shift1 dari tabel kategorishift
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensico.tanggal',
                'kategorishift.shift1'
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutData->whereBetween('absensico.tanggal', [$startDate, $endDate]);
        }

        $checkoutResults = $checkoutData->get();

        // Gabungkan data check-in dan check-out
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = $checkin->npk . '-' . $checkin->tanggal;
            $section = SectionModel::find($checkin->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Tentukan status berdasarkan waktu shift dan waktuci
            $status = 'Tepat Waktu';
            if ($checkin->waktuci > $checkin->shift1) {
                $status = 'Terlambat';
            }

            $results[$key] = [
                'nama' => $checkin->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $checkin->shift1,
                'section_nama' => $section ? $section->nama : 'Unknown',
                'department_nama' => $department ? $department->nama : 'Unknown',
                'division_nama' => $division ? $division->nama : 'Unknown',
                'status' => $status
            ];
        }

        foreach ($checkoutResults as $checkout) {
            $key = $checkout->npk . '-' . $checkout->tanggal;
            $section = SectionModel::find($checkout->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                $results[$key] = [
                    'nama' => $checkout->nama,
                    'npk' => $checkout->npk,
                    'tanggal' => $checkout->tanggal,
                    'waktuci' => null,
                    'waktuco' => $checkout->waktuco,
                    'shift1' => $checkout->shift1,
                    'section_nama' => $section ? $section->nama : 'Unknown',
                    'department_nama' => $department ? $department->nama : 'Unknown',
                    'division_nama' => $division ? $division->nama : 'Unknown',
                    'status' => 'Unknown' // Tidak bisa menentukan status jika tidak ada waktu check-in
                ];
            }
        }

        // Ubah hasil menjadi koleksi dan urutkan berdasarkan tanggal ascending
        // Ubah hasil menjadi koleksi dan urutkan berdasarkan tanggal descending (paling baru di atas)
        $finalResults = collect(array_values($results))->sortByDesc('tanggal');

        // Tampilkan "no in" dan "no out" jika waktuci atau waktuco null
        return DataTables::of($finalResults)
            ->addIndexColumn()
            ->editColumn('waktuci', function ($row) {
                return $row['waktuci'] ? $row['waktuci'] : 'NO IN'; // Tetap tampilkan Unknown jika waktuci tidak ada
            })
            ->editColumn('waktuco', function ($row) {
                return $row['waktuco'] ? $row['waktuco'] : 'NO OUT';
            })
            ->editColumn('shift1', function ($row) {
                return $row['shift1'] ? $row['shift1'] : 'NO SHIFT'; // Tampilkan NO SHIFT jika shift1 kosong
            })
            ->editColumn('status', function ($row) {
                if (!$row['shift1']) {
                    return 'NO SHIFT'; // Jika shift1 kosong, status menjadi NO SHIFT
                }
                if (!$row['waktuci']) {
                    return 'NO IN'; // Jika waktuci kosong, status menjadi NO IN
                }
                if ($row['waktuci'] > $row['shift1']) {
                    return 'Terlambat'; // Jika waktuci lebih besar dari shift1, status Terlambat
                }
                return 'Tepat Waktu'; // Jika waktuci kurang atau sama dengan shift1, status Tepat Waktu
            })
            ->make(true);
    }






    public function storeCheckin(Request $request)
    {
        $request->validate([

            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuci' => 'required|date_format:H:i',
        ]);

        absensici::create([

            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuci' => $request->waktuci,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function storeCheckout(Request $request)
    {
        $request->validate([

            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuco' => 'required|date_format:H:i',
        ]);

        absensico::create([

            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuco' => $request->waktuco,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function upload(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:txt,csv' // Memperbolehkan file CSV juga
        ]);

        // Cek apakah file valid
        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload failed. Please try again.');
        }

        // Simpan file ke storage (misalnya, di 'uploads' folder)
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

        if (!$filePath) {
            return redirect()->back()->withErrors('Failed to upload the file.');
        }

        // Dispatch job pertama untuk memproses file batch pertama
        UploadFileJob::dispatch($filePath);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'File sedang diproses di latar belakang.');
    }


    public function exportAbsensi(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $search = $request->input('search'); // Ambil parameter search

        return Excel::download(new RekapAbsensiExport($startDate, $endDate, $search), 'absensi.xlsx');
    }
    public function uploadapi(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:txt,csv' // Memperbolehkan file CSV juga
        ]);

        // Logging informasi tentang permintaan upload



        // Cek apakah file valid
        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload failed. Please try again.');
        }

        // Membaca isi file
        $fileContent = file($file->getRealPath());

        foreach ($fileContent as $line) {
            $data = str_getcsv($line, "\t"); // Menganggap file menggunakan tab sebagai pemisah

            if (count($data) >= 5) {
                $npk = $data[1];
                $tanggal = $data[2];
                $status = $data[3];
                $time = $data[4];

                // Mengonversi format tanggal dari dd.mm.yyyy ke yyyy-mm-dd
                $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                if ($date) {
                    $formattedDate = $date->format('Y-m-d'); // Mengonversi ke yyyy-mm-dd
                } else {
                    // Log error jika tanggal tidak valid
                    Log::error('Invalid date format', ['date' => $tanggal]);
                    continue;
                }

                if ($status == 'P10') {
                    Absensici::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuci' => $time]
                    );
                } elseif ($status == 'P20') {
                    Absensico::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuco' => $time]
                    );
                }
            } else {
                // Log bahwa baris tidak mengandung cukup data
                Log::warning('Insufficient data in line', ['line' => $line]);
            }
        }

        return response()->json(['success' => 'Data berhasil diupload!']);
    }
}
