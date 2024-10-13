<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\attendanceRecordModel;
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
        $today = date('Y-m-d'); // Mendapatkan tanggal hari ini

        // Query untuk mendapatkan data check-in
        $checkinData = DB::table('absensici')
            ->join('users', 'absensici.npk', '=', 'users.npk')
            ->leftJoin('kategorishift as ks', function ($join) {
                $join->on('absensici.npk', '=', 'ks.npk')
                    ->on('absensici.tanggal', '=', 'ks.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensici.tanggal',
                DB::raw('DATE_FORMAT(MIN(absensici.waktuci), "%H:%i") as waktuci'),
                DB::raw('(SELECT shift1 FROM kategorishift WHERE npk = absensici.npk AND date = absensici.tanggal ORDER BY created_at DESC LIMIT 1) as shift1')
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

        // Query untuk mendapatkan data check-out
        $checkoutData = DB::table('absensico')
            ->join('users', 'absensico.npk', '=', 'users.npk')
            ->leftJoin('kategorishift as ks', function ($join) {
                $join->on('absensico.npk', '=', 'ks.npk')
                    ->on('absensico.tanggal', '=', 'ks.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensico.tanggal',
                DB::raw('DATE_FORMAT(MAX(absensico.waktuco),"%H:%i") as waktuco'),
                DB::raw('(SELECT shift1 FROM kategorishift WHERE npk = absensico.npk AND date = absensico.tanggal ORDER BY created_at DESC LIMIT 1) as shift1')
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

        // Mengambil karyawan yang memiliki shift tapi tidak ada waktu check-in atau check-out
        $noCheckData = DB::table('kategorishift as ks')
            ->join('users', 'ks.npk', '=', 'users.npk')
            ->leftJoin('absensici', function ($join) {
                $join->on('absensici.npk', '=', 'ks.npk')
                    ->on('absensici.tanggal', '=', 'ks.date');
            })
            ->leftJoin('absensico', function ($join) {
                $join->on('absensico.npk', '=', 'ks.npk')
                    ->on('absensico.tanggal', '=', 'ks.date');
            })
            ->select(
                'users.nama',
                'users.npk',
                'ks.date as tanggal',
                'ks.shift1',
                DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
            )
            ->whereNull('absensici.waktuci') // Tidak ada waktu check-in
            ->whereNull('absensico.waktuco') // Tidak ada waktu check-out
            ->where('ks.date', '<=', $today) // Ambil data sebelum atau sama dengan hari ini
            ->groupBy('users.npk', 'ks.date', 'ks.shift1', 'users.nama')
            ->get();

        // Gabungkan hasil check-in, check-out, dan no-in no-out
        $results = [];
        foreach ($checkinResults as $checkin) {
            $key = $checkin->npk . '-' . $checkin->tanggal;
            $section = SectionModel::find($checkin->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Gabungkan data check-in
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
                attendanceRecordModel::updateOrCreate(
                    [
                        'npk' => $checkin->npk,
                        'tanggal' => $checkin->tanggal
                    ],
                    [
                        'waktuci' => $checkin->waktuci,
                        'shift1' => $checkin->shift1,
                        'status' => $status,
                        'waktuco' => null // Set waktu checkout sebagai null saat check-in
                    ]
                );
            }

            // Gabungkan data checkout
            foreach ($checkoutResults as $checkout) {
                $key = $checkout->npk . '-' . $checkout->tanggal;
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
                        'status' => 'Unknown'
                    ];
                }
            }

            // Gabungkan data no-in no-out
            foreach ($noCheckData as $noCheck) {
                $key = $noCheck->npk . '-' . $noCheck->tanggal;
                if (!isset($results[$key])) {
                    $results[$key] = [
                        'nama' => $noCheck->nama,
                        'npk' => $noCheck->npk,
                        'tanggal' => $noCheck->tanggal,
                        'waktuci' => 'NO IN',
                        'waktuco' => 'NO OUT',
                        'shift1' => $noCheck->shift1,
                        'section_nama' => $section ? $section->nama : 'Unknown',
                        'department_nama' => $department ? $department->nama : 'Unknown',
                        'division_nama' => $division ? $division->nama : 'Unknown',
                        'status' => 'NO IN & OUT'
                    ];
                    attendanceRecordModel::updateOrCreate(
                        [
                            'npk' => $checkin->npk,
                            'tanggal' => $checkin->tanggal
                        ],
                        [
                            'waktuci' => $checkin->waktuci,
                            'shift1' => $checkin->shift1,
                            'status' => $status,
                            'waktuco' => null // Set waktu checkout sebagai null saat check-in
                        ]
                    );
                }
            }

            // Ubah array menjadi collection untuk dikembalikan dalam DataTables
            $finalResults = collect(array_values($results))->sortByDesc('tanggal');

            return DataTables::of($finalResults)
                ->addIndexColumn()
                ->editColumn('waktuci', function ($row) {
                    return $row['waktuci'] ? $row['waktuci'] : 'NO IN';
                })
                ->editColumn('waktuco', function ($row) {
                    return $row['waktuco'] ? $row['waktuco'] : 'NO OUT';
                })
                ->editColumn('shift1', function ($row) {
                    return $row['shift1'] ? $row['shift1'] : 'NO SHIFT';
                })
                ->editColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
        }
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
