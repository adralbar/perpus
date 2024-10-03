<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use App\Models\DivisionModel;
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

        // Ambil data check-in
        $checkinData = DB::table('absensici')
            ->join('users', 'absensici.npk', '=', 'users.npk')
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensici.tanggal',
                DB::raw('MIN(absensici.waktuci) as waktuci')
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensici.tanggal'
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkinData->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $checkinResults = $checkinData->get();

        // Ambil data check-out
        $checkoutData = DB::table('absensico')
            ->join('users', 'absensico.npk', '=', 'users.npk')
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensico.tanggal',
                DB::raw('MAX(absensico.waktuco) as waktuco')
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensico.tanggal'
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

            // Pastikan section tidak null sebelum mencoba mengakses division_id
            $department = $section ? DepartmentModel::find($section->department_id) : null; // Ambil department berdasarkan section
            $division = $department ? DivisionModel::find($department->division_id) : null; // Ambil division berdasarkan section

            $results[$key] = [
                'nama' => $checkin->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null, // Default null untuk waktu checkout
                'section_nama' => $section ? $section->nama : 'Unknown',
                'department_nama' => $department ? $department->nama : 'Unknown',
                'division_nama' => $division ? $division->nama : 'Unknown', // Pastikan division_nama diambil dengan benar
            ];
        }

        // Proses untuk checkout
        foreach ($checkoutResults as $checkout) {
            $key = $checkout->npk . '-' . $checkout->tanggal;
            $section = SectionModel::find($checkout->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $section ? DivisionModel::find($section->division_id) : null;

            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
                $results[$key]['section_nama'] = $section ? $section->nama : 'Unknown';
                $results[$key]['department_nama'] = $department ? $department->nama : 'Unknown';
                $results[$key]['division_nama'] = $division ? $division->nama : 'Unknown';
            } else {
                $results[$key] = [
                    'nama' => $checkout->nama,
                    'npk' => $checkout->npk,
                    'tanggal' => $checkout->tanggal,
                    'waktuci' => null,
                    'waktuco' => $checkout->waktuco,
                    'section_nama' => $section ? $section->nama : 'Unknown',
                    'department_nama' => $department ? $department->nama : 'Unknown',
                    'division_nama' => $division ? $division->nama : 'Unknown',
                ];
            }
        }


        // Ubah hasil menjadi koleksi dan urutkan berdasarkan tanggal ascending
        $finalResults = collect(array_values($results))->sortBy('tanggal');

        // Tampilkan "no in" dan "no out" jika waktuci atau waktuco null
        return DataTables::of($finalResults)
            ->addIndexColumn()
            ->editColumn('waktuci', function ($row) {
                return $row['waktuci'] ? $row['waktuci'] : 'NO IN';
            })
            ->editColumn('waktuco', function ($row) {
                return $row['waktuco'] ? $row['waktuco'] : 'NO OUT';
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
