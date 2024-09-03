<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\absensiCoController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\rekapController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\performaController;
use App\Http\Controllers\registController;
// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [loginController::class, 'loginForm'])->name('login');
    Route::post('/login', [loginController::class, 'authenticate']);
});

Route::middleware(['auth'])->group(function () {
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
});
Route::get('/registerperformaapi123', [registController::class, 'showRegistrationForm'])->name('register.form');

// Menangani proses registrasi
Route::post('/registerperformaapi123', [registController::class, 'register'])->name('register');
