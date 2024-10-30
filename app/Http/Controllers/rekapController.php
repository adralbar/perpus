<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\absensici;
use App\Models\absensico;
use App\Models\RecapAbsensi;
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
use Illuminate\Support\Facades\Artisan;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\Auth;

class rekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $sectionId = $user->section_id;

        $query = User::select('nama', 'npk');

        if ($roleId == 2) {
            $query->where('section_id', $sectionId);
        }

        $userData = $query->get();
        return view('rekap.rekapAbsensi', compact('userData'));

    }

    public function getRecapDataApi(Request $request)
    {
        $data = [];

        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');
        $npk = $request->query('npk');

        RecapAbsensi::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })
            ->when($npk, function ($query) use ($npk) {
                $query->where('npk', $npk);
            })
            ->chunk(100, function ($records) use (&$data) {
                foreach ($records as $record) {
                    $data[] = $record;
                }
            });

        return response()->json(['data' => $data]);
    }



    public function getDataApi()
    {
        $checkinQuery = Absensici::select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal');

        $checkoutQuery = Absensico::select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal');

        $checkinResults = $checkinQuery->get()->keyBy(fn($item) => "{$item->npk}-{$item->tanggal}");
        $checkoutResults = $checkoutQuery->get()->keyBy(fn($item) => "{$item->npk}-{$item->tanggal}");

        $results = [];
        foreach ($checkinResults as $key => $checkin) {
            $checkout = $checkoutResults->get($key);

            $user = User::where('npk', $checkin->npk)->first();
            $section = $user->section ?? null;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;
            $npkSistem = $user->npk_sistem ?? null;

            // Ambil shift yang terakhir sesuai tanggal
            $latestShift = Shift::where('npk', $checkin->npk)
                ->where('date', $checkin->tanggal)
                ->latest()
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : 'No shift';

            // Tentukan status berdasarkan waktu check-in dan shift
            $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null;
            $status = $latestShift && $checkin->waktuci > $shiftIn ? 'Terlambat' : 'Tepat Waktu';

            // Data untuk rekab absensi
            $rekabData = [
                'nama' => $user->nama ?? '',
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci ?: 'NO IN',
                'waktuco' => $checkout->waktuco ?? 'NO OUT',
                'shift1' => $shift1,
                'section_nama' => $section->nama ?? '',
                'department_nama' => $department->nama ?? '',
                'division_nama' => $division->nama ?? '',
                'status' => $status,
                'npk_sistem' => $npkSistem,
            ];

            // Insert atau update ke dalam tabel rekabAbsensi
            RecapAbsensi::updateOrCreate(
                [
                    'npk' => $checkin->npk,
                    'tanggal' => $checkin->tanggal
                ],
                $rekabData
            );

            $results[] = $rekabData;
        }

        // Kembalikan hasil akhir sebagai respons JSON
        return response()->json(['data' => $results]);
    }


    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $id = $request->input('id');

        if (!empty($id)) {
            $checkin = Absensici::with(['user', 'shift'])
                ->where('id', $id)
                ->first();

            $checkout = Absensico::with(['user', 'shift'])
                ->where('id', $id)
                ->first();

            return response()->json([
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => $checkout ? $checkout->waktuco : 'NO OUT',
                'nama' => $checkin->user ? $checkin->user->nama : '',
                'section_nama' => $checkin->user && $checkin->user->section ? $checkin->user->section->nama : '',
                'department_nama' => $checkin->user && $checkin->user->section && $checkin->user->section->department ? $checkin->user->section->department->nama : '',
                'division_nama' => $checkin->user && $checkin->user->section && $checkin->user->section->department && $checkin->user->section->department->division ? $checkin->user->section->department->division->nama : '',
                'shift1' => $checkin->shift ? $checkin->shift->shift1 : null
            ]);
        }

        $checkinQuery = Absensici::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        if ($request->has('npk') && !empty($request->npk)) {
            $checkinQuery->whereIn('npk', $request->npk);  // Perbaikan disini
        }
        $checkinResults = $checkinQuery->get();

        // Query Check-out
        $checkoutQuery = Absensico::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        if ($request->has('npk') && !empty($request->npk)) {
            $checkoutQuery->whereIn('npk', $request->npk);  // Perbaikan disini
        }
        $checkoutResults = $checkoutQuery->get();

        // Gabungkan data check-in dan check-out
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = "{$checkin->npk}-{$checkin->tanggal}";

            // Ambil relasi User, Section, dan Shift
            $section = $checkin->user ? $checkin->user->section : null;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;

            $latestShift = Shift::where('npk', $checkin->npk)
                ->where('date', $checkin->tanggal)
                ->latest()
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;

            $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null;
            $shiftInFormatted = $shiftIn ? date('H:i:s', strtotime($shiftIn)) : null;

            $status = 'No Shift';
            if ($latestShift) {
                $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
            }

            // Data hasil akhir untuk Check-in
            $results[$key] = [
                'nama' => $checkin->user ? $checkin->user->nama : '',
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : '',
                'department_nama' => $department ? $department->nama : '',
                'division_nama' => $division ? $division->nama : '',
                'status' => $status
            ];
        }

        // Tambahkan waktu checkout
        foreach ($checkoutResults as $checkout) {
            $key = "{$checkout->npk}-{$checkout->tanggal}";

            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                // Jika checkout adalah pada hari berikutnya
                $previousDay = date('Y-m-d', strtotime("{$checkout->tanggal} -1 day"));
                $previousKey = "{$checkout->npk}-{$previousDay}";

                if (isset($results[$previousKey]) && !$results[$previousKey]['waktuco']) {
                    $results[$previousKey]['waktuco'] = $checkout->waktuco;
                } else {
                    $results[$key] = [
                        'nama' => $checkout->user ? $checkout->user->nama : '',
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco,
                        'shift1' => optional($checkout->shift)->shift1,
                        'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
                        'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
                        'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
                        'status' => 'Unknown'
                    ];
                }
            }
        }

        $finalResults = collect(array_values($results))->sortBy('tanggal');
        foreach ($finalResults as $key => $row) {
            $npk = $row['npk'];
            $tanggalMulai = $row['tanggal'] ?? null;

            if (!$tanggalMulai) {
                Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
                continue;
            }

            $user = User::where('npk', $npk)->first();

            $npkSistem = $user->npk_sistem;

            $penyimpanganCount = Penyimpanganmodel::where('npk', $npk)
                ->where('tanggal_mulai', $tanggalMulai)
                ->count();
            $kategoriPenyimpangan = Penyimpanganmodel::where('npk', $npk)
                ->where('tanggal_mulai', $tanggalMulai)
                ->pluck('kategori')
                ->first();

            $aksi = '<button class="btn btn-primary btn-sm btn-edit" data-id="' . $id . '">Edit</button>';

            $apiTime = null;
            if ($penyimpanganCount > 0) {
                $apiTime = '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $tanggalMulai . '">Lihat Penyimpangan</button>';
            }

            $finalResults->put($key, array_merge($row, [
                'has_penyimpangan' => $penyimpanganCount > 0,
                'api_time' => $apiTime,
                'npk_sistem' => $npkSistem,
                'aksi' => $aksi,
                'status' => !empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status'],
            ]));
        }
        $currentId = 1;
        $finalResults = $finalResults->map(function ($row) use (&$currentId) {
            $row['id'] = $currentId++;
            $row['waktuci'] = $row['waktuci'] ?: 'NO IN';
            $row['waktuco'] = $row['waktuco'] ?: 'NO OUT';
            return $row;
        });

        $data = [];
        foreach ($finalResults as $item) {
            $data[] = $item;
        }

        return response()->json([
            "data" => $data,
        ]);
    }

    public function getPenyimpangan(Request $request)
    {
        $no = 1;

        $npk = $request->get('npk');
        $tanggal = $request->get('tanggal');

        $penyimpangan = PenyimpanganModel::where('npk', $npk)
            ->where('tanggal_mulai', $tanggal)
            ->get();

        // Looping untuk manipulasi data yang diambil
        foreach ($penyimpangan as $item) {
            // Cek berdasarkan nilai approved_by
            if ($item->approved_by == 4) {
                $item->approved_by = '<span class="badge badge-success">Approved by Department</span>';
            } elseif ($item->approved_by == 3) {
                $item->approved_by = '<span class="badge badge-success">Approved by Section</span>';
            } elseif ($item->approved_by == 5) {
                $item->approved_by = '<span class="badge badge-success">Approved by Division</span>';
            } elseif ($item->rejected_by == 5) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Division</span>';
            } elseif ($item->rejected_by == 4) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Department</span>';
            } elseif ($item->rejected_by == 3) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Section</span>';
            } else {
                $item->status = $item->sent == 1 ? '<span class="badge badge-secondary">Need Approval</span>' : 'Not Sent';
            }

            if (!empty($item->foto)) {
                $item->file_upload = !empty($item->foto) ? '<button class="btn btn-primary btn-sm" onclick="showImage(\'' . asset('storage/' . $item->foto) . '\')">Lihat Foto</button>' : '';
            } else {
                $item->file_upload = ' ';
            }
            $item->no = $no++;
        }

        return response()->json([
            'data' => $penyimpangan,
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
        $request->validate([
            'file' => 'required|mimes:txt|max:250', 
        ]);
    
        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload gagal. Silakan coba lagi.');
        }
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName());
    
        if (!$filePath) {
            return redirect()->back()->withErrors('Gagal mengunggah file.');
        }
    
        $fileContent = file(storage_path('app/' . $filePath));
        $batchSize = 100;
        $totalLines = count($fileContent);
    
        for ($offset = 0; $offset < $totalLines; $offset += $batchSize) {
            $batch = array_slice($fileContent, $offset, $batchSize);
    
            foreach ($batch as $line) {
                $data = str_getcsv($line, "\t");
    
                if (count($data) >= 5) {
                    $npk_sistem = $data[1];
                    $tanggal = $data[2];
                    $status = $data[3];
                    $time = $data[4];
    
                    $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                    if ($date) {
                        $formattedDate = $date->format('Y-m-d');
                    } else {
                        Log::error('Format tanggal tidak valid', ['date' => $tanggal]);
                        continue;
                    }
    
                    $user = User::where('npk_sistem', $npk_sistem)->first();
    
                    if ($user) {
                        $section = $user->section ?? null;
                        $department = $section ? $section->department : null;
                        $division = $department ? $department->division : null;
    
                        $latestShift = Shift::where('npk', $user->npk)
                            ->where('date', $formattedDate)
                            ->latest()
                            ->first();
                        $shift1 = $latestShift ? $latestShift->shift1 : 'No shift';
    
                        $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null;
                        $attendanceStatus = $latestShift && $status === 'P10' && $time > $shiftIn ? 'Terlambat' : 'Tepat Waktu';
    
                        $existingRecap = RecapAbsensi::where('npk_sistem', $npk_sistem)
                            ->where('tanggal', $formattedDate)
                            ->first();
    
                        $waktuci = $status === 'P10' 
                            ? ($existingRecap && $existingRecap->waktuci ? $existingRecap->waktuci : $time)
                            : $existingRecap->waktuci ?? null;
    
                        $waktuco = $status === 'P20' 
                            ? ($existingRecap && $existingRecap->waktuco ? max($existingRecap->waktuco, $time) : $time)
                            : $existingRecap->waktuco ?? null;
    
                        RecapAbsensi::updateOrCreate(
                            [
                                'npk_sistem' => $npk_sistem,
                                'tanggal' => $formattedDate
                            ],
                            [
                                'nama' => $user->nama,
                                'npk' => $user->npk,
                                'waktuci' => $waktuci,
                                'waktuco' => $waktuco,
                                'shift1' => $shift1,
                                'section_nama' => $section->name ?? '',
                                'department_nama' => $department->name ?? '',
                                'division_nama' => $division->name ?? '',
                                'status' => $attendanceStatus,
                            ]
                        );
                    } else {
                        Log::error('User dengan npk_sistem tidak ditemukan', ['npk_sistem' => $npk_sistem]);
                    }
                } else {
                    Log::warning('Data tidak lengkap dalam baris', ['line' => $line]);
                }
            }
        }
    
        return redirect()->back()->with('success', 'File berhasil diunggah dan diproses.');
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

    // Tambahkan method baru di rekapController
    // public function getDataByNpkTanggal($npk, $tanggal)
    // {
    //     $checkinData = Absensici::with(['user', 'shift', 'user.section.department.division'])
    //         ->where('npk', $npk)
    //         ->where('tanggal', $tanggal)
    //         ->first();

    //     $checkoutData = Absensico::where('npk', $npk)
    //         ->where('tanggal', $tanggal)
    //         ->first();

    //     $data = [
    //         'npk' => $checkinData->npk,
    //         'tanggal' => $checkinData->tanggal,
    //         'waktuci' => $checkinData->waktuci,
    //         'waktuco' => $checkoutData ? $checkoutData->waktuco : 'NO OUT',
    //         'nama' => $checkinData->user->nama,
    //         'section_nama' => optional($checkinData->user->section)->nama,
    //         'department_nama' => optional($checkinData->user->section->department)->nama,
    //         'division_nama' => optional($checkinData->user->section->department->division)->nama,
    //         'shift1' => optional($checkinData->shift)->shift1,
    //     ];

    //     return response()->json($data);
    // }

    public function updateData(Request $request, $npk, $tanggal)
    {
        $validated = $request->validate([
            'waktuci' => 'nullable',
            'waktuco' => 'nullable',
        ]);

        $checkin = Absensici::where('npk', $npk)->where('tanggal', $tanggal)->first();
        $checkout = Absensico::where('npk', $npk)->where('tanggal', $tanggal)->first();

        if ($checkin) {
            $checkin->waktuci = $validated['waktuci'];
            $checkin->save();
        }

        if ($checkout) {
            $checkout->waktuco = $validated['waktuco'];
            $checkout->save();
        }

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }
}
