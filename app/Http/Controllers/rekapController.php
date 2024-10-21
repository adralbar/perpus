<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\shift;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Events\FileUploaded;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use App\Models\PenyimpanganModel;
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
        $today = date('Y-m-d', strtotime('-1 day'));
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query untuk mendapatkan data check-in
        $checkinQuery = Absensici::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $checkinResults = $checkinQuery->get();

        // Query untuk mendapatkan data check-out
        $checkoutQuery = Absensico::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $checkoutResults = $checkoutQuery->get();

        // Gabungkan data check-in dan check-out
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = "{$checkin->npk}-{$checkin->tanggal}";
            $section = $checkin->user->section;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;

            // Ambil shift terbaru
            $latestShift = $checkin->shift()->latest()->first(); // Dapatkan shift terbaru
            $shift1 = $latestShift ? $latestShift->shift1 : null; // Dapatkan shift dalam format "H:i - H:i"

            // Ambil waktu masuk dari shift
            $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null; // Ambil waktu pertama sebagai waktu masuk

            // Ubah waktu masuk ke format H:i:s
            $shiftInFormatted = $shiftIn ? date('H:i:s', strtotime($shiftIn)) : null; // Ganti '.' dengan ':' sebelum konversi

            // Debugging output

            // Tentukan status berdasarkan waktu shift dan waktuci

            if ($checkin->waktuci > $shiftInFormatted) {
                $status = 'Terlambat';
            } else {
                $status = 'Tepat Waktu';
            }

            // Hasil akhir

            $results[$key] = [
                'nama' => $checkin->user->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : 'Unknown',
                'department_nama' => $department ? $department->nama : 'Unknown',
                'division_nama' => $division ? $division->nama : 'Unknown',
                'status' => $status
            ];
        }

        foreach ($checkoutResults as $checkout) {
            $key = "{$checkout->npk}-{$checkout->tanggal}";

            if (isset($results[$key])) {
                // Jika ada check-in di tanggal yang sama, update waktu checkout
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                // Cek apakah ada check-in pada hari sebelumnya
                $previousDay = date('Y-m-d', strtotime("{$checkout->tanggal} -1 day"));
                $previousKey = "{$checkout->npk}-{$previousDay}";

                if (isset($results[$previousKey]) && !$results[$previousKey]['waktuco']) {
                    // Jika ada check-in pada hari sebelumnya dan belum ada check-out, gabungkan data
                    $results[$previousKey]['waktuco'] = $checkout->waktuco;
                } else {
                    // Jika tidak ada check-in pada hari sebelumnya, buat entri baru untuk check-out
                    $results[$key] = [
                        'nama' => $checkout->user->nama,
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco,
                        'shift1' => optional($checkout->shift)->shift1,
                        'section_nama' => $checkout->user->section ? $checkout->user->section->nama : 'Unknown',
                        'department_nama' => $checkout->user->section->department ? $checkout->user->section->department->nama : 'Unknown',
                        'division_nama' => $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : 'Unknown',
                        'status' => 'Unknown' // Tidak bisa menentukan status jika tidak ada waktu check-in
                    ];
                }
            }
        }

        // Data untuk karyawan yang tidak check-in dan check-out
        $noCheckData = Shift::with(['user.section.department.division'])
            ->leftJoin('absensici', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->on('absensici.tanggal', '=', 'kategorishift.date');
            })
            ->leftJoin('absensico', function ($join) {
                $join->on('absensico.npk', '=', 'kategorishift.npk')
                    ->on('absensico.tanggal', '=', 'kategorishift.date');
            })
            ->select(
                'kategorishift.npk',
                'kategorishift.date as tanggal',
                'kategorishift.shift1',
                DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
            )
            ->whereNull('absensici.waktuci')
            ->whereNull('absensico.waktuco')
            ->where('kategorishift.date', '<=', $today)
            ->where('kategorishift.shift1', '!=', 'OFF')
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();

        foreach ($noCheckData as $noCheck) {
            $key = "{$noCheck->npk}-{$noCheck->tanggal}";

            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : 'Unknown',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => 'NO IN',
                    'waktuco' => 'NO OUT',
                    'shift1' => $noCheck->shift1,
                    'section_nama' => $noCheck->user->section ? $noCheck->user->section->nama : 'Unknown',
                    'department_nama' => $noCheck->user->section->department ? $noCheck->user->section->department->nama : 'Unknown',
                    'division_nama' => $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : 'Unknown',
                    'status' => 'Mangkir'
                ];
            }
        }

        // Mengubah hasil akhir menjadi koleksi dan mengurutkan berdasarkan tanggal
        $finalResults = collect(array_values($results))->sortByDesc('tanggal');

        // Menambahkan logika untuk memeriksa penyimpangan
        foreach ($finalResults as $key => $row) {
            $npk = $row['npk'];
            $tanggalMulai = $row['tanggal'] ?? null;

            if (!$tanggalMulai) {
                Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
                continue; // Lewati iterasi ini jika tanggal tidak ada
            }

            $penyimpanganCount = Penyimpanganmodel::where('npk', $npk)
                ->where('tanggal_mulai', $tanggalMulai)
                ->count();

            // Menggunakan metode put untuk menambahkan elemen baru ke dalam koleksi
            $finalResults->put($key, array_merge($row, [
                'has_penyimpangan' => $penyimpanganCount > 0
            ]));
        }

        // Mengembalikan hasil ke AJAX
        return DataTables::of($finalResults)
            ->addIndexColumn()
            ->editColumn('waktuci', function ($row) {
                return $row['waktuci'] ?: 'NO IN';
            })
            ->editColumn('waktuco', function ($row) {
                return $row['waktuco'] ?: 'NO OUT';
            })
            ->editColumn('shift1', function ($row) {
                return $row['shift1'] ?: 'NO SHIFT';
            })

            ->make(true);
    }


    public function getPenyimpangan()
    {
        $no = 1;

        $data = PenyimpanganModel::all();

        foreach ($data as $item) {
            if ($item->approved_by == 4) {
                $item->status = '<span class="badge badge-success">Approved by Department</span>';
                $item->aksi = '<button class="btn btn-secondary btn-sm" disabled>Approved</button>';
            } elseif ($item->rejected_by == 4) {
                $item->status = '<span class="badge badge-danger">Rejected by Department</span>';
                $item->aksi = '<button class="btn btn-secondary btn-sm" disabled>Rejected</button>';
            } else {
                $item->status = $item->sent == 1 ? '<span class="badge badge-secondary">Need Approval</span>' : 'Not Sent';
            }

            if (!empty($item->foto)) {
                $item->file_upload .= ' <button class="btn btn-primary btn-sm" onclick="showImage(\'' . asset('storage/' . $item->foto) . '\')">Lihat Foto</button>';
            } else {
                $item->file_upload .= ' ';
            }

            $item->no = $no++;
        }

        return response()->json([
            'data' => $data,

        ]);
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

        UploadFileJob::dispatch($filePath);

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

        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload failed. Please try again.');
        }

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
                Log::warning('Insufficient data in line', ['line' => $line]);
            }
        }

        return response()->json(['success' => 'Data berhasil diupload!']);
    }
}
