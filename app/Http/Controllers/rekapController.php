<?php

namespace App\Http\Controllers;

use App\Models\absensici;
use Illuminate\Support\Facades\DB;
use App\Models\absensico;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use DateTime;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $month = $request->input('month');
        $year = date('Y'); // You can also make this dynamic if needed

        // Debugging: Check if month parameter is received
        Log::info('Filter Month: ' . $month);

        $query = DB::table('absensici')
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal')
                    ->on('absensici.waktuci', '=', 'first_checkin.waktuci');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('absensici as next_checkin')
                            ->whereRaw('next_checkin.npk = absensici.npk')
                            ->whereRaw('next_checkin.tanggal = DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)')
                            ->whereRaw('next_checkin.waktuci < last_checkout_tomorrow.waktuco');
                    });
            })
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                'absensici.tanggal',
                'first_checkin.waktuci as waktuci',
                DB::raw('COALESCE(last_checkout_tomorrow.waktuco, last_checkout_today.waktuco) as waktuco')
            )
            ->distinct()
            ->groupBy('absensici.npk', 'absensici.tanggal', 'kategorishift.nama', 'kategorishift.npkSistem', 'kategorishift.divisi', 'kategorishift.departement', 'kategorishift.section', 'first_checkin.waktuci', 'last_checkout_today.waktuco', 'last_checkout_tomorrow.waktuco')
            ->orderBy('absensici.tanggal', 'desc');

        // Apply the month filter if it is set
        if (!empty($month)) {
            $query->whereMonth('absensici.tanggal', $month)
                ->whereYear('absensici.tanggal', $year); // Optional: Filter by year as well
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
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
        $request->validate([
            'file' => 'required|mimes:txt'
        ]);

        $file = $request->file('file');
        $fileContent = file($file->getRealPath());

        foreach ($fileContent as $line) {
            $data = str_getcsv($line, "\t"); // Assuming the file uses tabs as delimiters

            if (count($data) >= 5) {
                $npk = $data[1];
                $tanggal = $data[2];
                $status = $data[3];
                $time = $data[4];

                // Convert the date format from dd.mm.yyyy to yyyy-mm-dd
                $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                if ($date) {
                    $formattedDate = $date->format('Y-m-d'); // Converts to yyyy-mm-dd
                } else {
                    // Handle error if the date is invalid
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
            }
        }

        return redirect()->back()->with('success', 'File uploaded and data processed successfully.');
    }

    public function exportAbsensi(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return Excel::download(new RekapAbsensiExport($month, $year), 'absensi.xlsx');
    }
}
