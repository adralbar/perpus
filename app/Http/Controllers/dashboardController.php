<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\SectionModel;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard');
    }
}
