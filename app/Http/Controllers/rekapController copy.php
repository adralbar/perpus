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
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Debugging: Check if month parameter is received
        Log::info('Filter Month: ' . $startDate);
        Log::info('Filter Month: ' . $endDate);

        $query = DB::table('absensici')
            // Ganti menjadi LEFT JOIN
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
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
                // Jika waktuci NULL, tampilkan 'NO IN'
                DB::raw('IFNULL(first_checkin.waktuci, "NO IN") as waktuci'),
                // Jika waktuco NULL, tampilkan 'NO OUT'
                DB::raw('IFNULL(COALESCE(last_checkout_tomorrow.waktuco, last_checkout_today.waktuco), "NO OUT") as waktuco')
            )
            ->distinct()
            ->groupBy(
                'absensici.npk',
                'absensici.tanggal',
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'first_checkin.waktuci',
                'last_checkout_today.waktuco',
                'last_checkout_tomorrow.waktuco'
            )
            ->orderBy('absensici.tanggal', 'desc');


        // Apply the month filter if it is set
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }





    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query untuk absensici (check-in)
        $queryAbsensici = DB::table('absensici')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal');
            })
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                DB::raw('IFNULL(first_checkin.tanggal, DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)) as tanggal'),
                DB::raw('IFNULL(first_checkin.waktuci, "NO IN") as waktuci'),
                DB::raw('IFNULL(COALESCE(MAX(last_checkout_today.waktuco), MAX(last_checkout_tomorrow.waktuco)), "NO OUT") as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                'first_checkin.tanggal',
                'absensici.tanggal'
            );

        // Query untuk absensico (check-out)
        $queryAbsensico = DB::table('absensico')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensico.npk', '=', 'first_checkin.npk')
                    ->on('absensico.tanggal', '=', 'first_checkin.tanggal');
            })
            ->join('kategorishift', 'absensico.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                DB::raw('IFNULL(first_checkin.waktuci, "NO IN") as waktuci'),
                DB::raw('MAX(absensico.waktuco) as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                'first_checkin.waktuci'
            );

        // Gabungkan kedua query dengan UNION ALL
        $finalQuery = $queryAbsensici->unionAll($queryAbsensico);

        // Filter berdasarkan tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $finalQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Mengambil hasil akhir dengan pengelompokan
        $data = DB::table(DB::raw("({$finalQuery->toSql()}) as combined"))
            ->mergeBindings($finalQuery)
            ->select(
                'nama',
                'npkSistem',
                'divisi',
                'departement',
                'section',
                'npk',
                DB::raw('DATE(COALESCE(MAX(tanggal), DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY))) as tanggal'),
                DB::raw('IFNULL(MIN(waktuci), "NO IN") as waktuci'),
                DB::raw('IFNULL(MAX(waktuco), "NO OUT") as waktuco')
            )
            ->groupBy('nama', 'npkSistem', 'divisi', 'departement', 'section', 'npk')
            ->havingRaw('waktuci != "NO IN" OR waktuco != "NO OUT"')
            ->get();

        // Debugging untuk melihat hasil final query
        Log::info($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
//1 tanggal 1
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query untuk absensici (check-in)
        $queryAbsensici = DB::table('absensici')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal');
            })
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                DB::raw('IFNULL(first_checkin.tanggal, absensici.tanggal) as tanggal'),
                DB::raw('MIN(first_checkin.waktuci) as waktuci'),
                DB::raw('MAX(COALESCE(last_checkout_today.waktuco, last_checkout_tomorrow.waktuco)) as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                'first_checkin.tanggal',
                'absensici.tanggal'
            );

        // Query untuk absensico (check-out)
        $queryAbsensico = DB::table('absensico')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensico.npk', '=', 'first_checkin.npk')
                    ->on('absensico.tanggal', '=', 'first_checkin.tanggal');
            })
            ->join('kategorishift', 'absensico.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                DB::raw('MIN(first_checkin.waktuci) as waktuci'),
                DB::raw('MAX(absensico.waktuco) as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                'first_checkin.waktuci'
            );

        // Gabungkan kedua query dengan UNION ALL
        $finalQuery = $queryAbsensici->unionAll($queryAbsensico);

        // Filter berdasarkan tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $finalQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Mengambil hasil akhir dengan pengelompokan per npk dan tanggal
        $data = DB::table(DB::raw("({$finalQuery->toSql()}) as combined"))
            ->mergeBindings($finalQuery)
            ->select(
                'nama',
                'npkSistem',
                'divisi',
                'departement',
                'section',
                'npk',
                'tanggal',
                DB::raw('MIN(waktuci) as waktuci'),
                DB::raw('MAX(waktuco) as waktuco')
            )
            ->groupBy('nama', 'npkSistem', 'divisi', 'departement', 'section', 'npk', 'tanggal')
            ->havingRaw('waktuci IS NOT NULL OR waktuco IS NOT NULL') // Filter untuk hanya hasil valid
            ->get();

        // Debugging untuk melihat hasil final query
        Log::info($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    bisa banyak tanggal waktuco gamuncul
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query untuk absensici (check-in)
        $queryAbsensici = DB::table('absensici')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal');
            })
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                DB::raw('IFNULL(first_checkin.tanggal, absensici.tanggal) as tanggal'),
                DB::raw('MIN(first_checkin.waktuci) as waktuci'),
                DB::raw('MAX(COALESCE(last_checkout_today.waktuco, last_checkout_tomorrow.waktuco)) as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                'first_checkin.tanggal',
                'absensici.tanggal'
            );

        // Query untuk absensico (check-out)
        $queryAbsensico = DB::table('absensico')
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensico.npk', '=', 'first_checkin.npk')
                    ->on('absensico.tanggal', '=', 'first_checkin.tanggal');
            })
            ->join('kategorishift', 'absensico.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                DB::raw('MIN(first_checkin.waktuci) as waktuci'),
                DB::raw('MAX(absensico.waktuco) as waktuco')
            )
            ->groupBy(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal'
            );

        // Gabungkan kedua query dengan UNION ALL
        $finalQuery = $queryAbsensici->unionAll($queryAbsensico);

        // Filter berdasarkan tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $finalQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Mengambil hasil akhir dengan pengelompokan per npk dan tanggal
        $data = DB::table(DB::raw("({$finalQuery->toSql()}) as combined"))
            ->mergeBindings($finalQuery)
            ->select(
                'nama',
                'npkSistem',
                'divisi',
                'departement',
                'section',
                'npk',
                'tanggal',
                DB::raw('MIN(waktuci) as waktuci'),
                DB::raw('MAX(waktuco) as waktuco')
            )
            ->groupBy('nama', 'npkSistem', 'divisi', 'departement', 'section', 'npk', 'tanggal')
            ->havingRaw('waktuci IS NOT NULL AND waktuco IS NOT NULL') // Filter untuk hanya hasil valid
            ->get();

        // Debugging untuk melihat hasil final query
        Log::info($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    fix
    public function getData(Request $request)
    {
        // Retrieve start and end dates from the request
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query to get check-in data
        $checkinData = DB::table('absensici')
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensici.npk',
                'absensici.tanggal',
                DB::raw('MIN(absensici.waktuci) as waktuci')
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkinData->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $checkinResults = $checkinData->groupBy(
            'kategorishift.nama',
            'kategorishift.npkSistem',
            'kategorishift.divisi',
            'kategorishift.departement',
            'kategorishift.section',
            'absensici.npk',
            'absensici.tanggal'
        )->get();

        // Query to get check-out data
        $checkoutData = DB::table('absensico')
            ->join('kategorishift', 'absensico.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'absensico.npk',
                'absensico.tanggal',
                DB::raw('MAX(absensico.waktuco) as waktuco')
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutData->whereBetween('absensico.tanggal', [$startDate, $endDate]);
        }

        $checkoutResults = $checkoutData->groupBy(
            'kategorishift.nama',
            'kategorishift.npkSistem',
            'kategorishift.divisi',
            'kategorishift.departement',
            'kategorishift.section',
            'absensico.npk',
            'absensico.tanggal'
        )->get();

        // Merge check-in and check-out data by npk and dates
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = $checkin->npk . '-' . $checkin->tanggal;
            $results[$key] = [
                'nama' => $checkin->nama,
                'npkSistem' => $checkin->npkSistem,
                'divisi' => $checkin->divisi,
                'departement' => $checkin->departement,
                'section' => $checkin->section,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null
            ];
        }

        foreach ($checkoutResults as $checkout) {
            $key = $checkout->npk . '-' . $checkout->tanggal;

            // Merge with existing check-in data
            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                // Handle case where check-in happens the previous day
                $previousDayKey = $checkout->npk . '-' . Carbon::parse($checkout->tanggal)->subDay()->toDateString();
                if (isset($results[$previousDayKey]) && $results[$previousDayKey]['waktuci'] != null) {
                    // Merge check-out from the next day into the previous day's entry
                    $results[$previousDayKey]['waktuco'] = $checkout->waktuco;
                } else {
                    // If no matching check-in, treat as a new row
                    $results[$key] = [
                        'nama' => $checkout->nama,
                        'npkSistem' => $checkout->npkSistem,
                        'divisi' => $checkout->divisi,
                        'departement' => $checkout->departement,
                        'section' => $checkout->section,
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco
                    ];
                }
            }
        }

        // Convert the results back to a collection
        $finalResults = collect(array_values($results));

        return DataTables::of($finalResults)
            ->addIndexColumn()
            ->make(true);
    }
}