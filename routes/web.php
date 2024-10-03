<?php

use Database\Seeders\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\rekapController;
use App\Http\Controllers\shiftController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\registController;
use App\Http\Controllers\uploadController;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\performaController;
use App\Http\Controllers\absensiCoController;
use App\Http\Controllers\dashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [loginController::class, 'loginForm'])->name('login');
    Route::post('/login', [loginController::class, 'authenticate']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user', [UsersController::class, 'index'])->name('karyawan.index');
    Route::post('/user', [UsersController::class, 'store']);
    Route::get('/user/detail/{npk}', [UsersController::class, 'detail']);
    Route::get('/user/edit/{npk}', [UsersController::class, 'edit']);
    Route::put('/user/update/{npk}', [UsersController::class, 'update']);
    Route::delete('/user/delete/{npk}', [UsersController::class, 'destroy']);

    Route::resource('absensiControllerAjax', absensiController::class);
    Route::resource('absensiCoControllerAjax', absensiCoController::class);
    Route::get('/rekap', [rekapController::class, 'index'])->name('rekap.index');
    Route::get('/get-data', [rekapController::class, 'getData'])->name('rekap.getData');
    Route::post('/rekap/checkin', [rekapController::class, 'storeCheckin'])->name('rekap.storeCheckin');
    Route::post('/rekap/checkout', [rekapController::class, 'storeCheckout'])->name('rekap.storeCheckout');
    Route::get('/', [dashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/data-table1', [dashboardController::class, 'getTable1Data'])->name('data.table1');
    Route::get('/data-table2', [dashboardController::class, 'getTable2Data'])->name('data.table2');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
    Route::get('/performa', [performaController::class, 'index'])->name('performa.index');
    Route::get('/data-table1/details', [dashboardController::class, 'getTable1Details'])->name('data.table1.details');
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::get('/data/chart', [dashboardController::class, 'getChartData'])->name('data.chart');
    // Route::get('/data/table1', [dashboardController::class, 'getTable1Data'])->name('data.table1');
    Route::get('/performa/get-data', [performaController::class, 'getData'])->name('performa.getData');
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/getChartData', [dashboardController::class, 'getChartData'])->name('getChartData');
    Route::get('data/table1', [dashboardController::class, 'getTable1bData'])->name('data.table1b');
    Route::post('/performa/storelog', [performaController::class, 'storeLogs'])->name('performa.storeLogs');
    Route::post('/performa/storeuserid', [performaController::class, 'storeUserId'])->name('performa.storeUserId');
    Route::get('/shift', [shiftController::class, 'index'])->name('shift');
    Route::get('shift-data', [shiftController::class, 'getData'])->name('shift.data');




    Route::post('/store', [shiftController::class, 'store'])->name('shift.store');
    Route::get('/shift-data/{id}', [shiftController::class, 'edit'])->name('shift.edit');
    Route::put('/update/{id}', [shiftController::class, 'update'])->name('shift.update');
    Route::delete('/destroy/{id}', [shiftController::class, 'destroy'])->name('shift.destroy');

    Route::post('/fileupload', [shiftController::class, 'importProcess'])->name('shift.import');
    Route::get('/rekap/export', [rekapController::class, 'exp   ortAbsensi'])->name('rekap.export');
    Route::get('/performa/export', [performaController::class, 'performaExport'])->name('performa.export');
    Route::post('/upload', [rekapController::class, 'upload'])->name('upload');
    Route::get('/karyawandata', [UsersController::class, 'karyawandata'])->name('karyawandata');
});



Route::get('/registerperformaapi123', [registController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/registerperformaapi123', [registController::class, 'register'])->name('register');
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrfToken' => csrf_token()]);
});
