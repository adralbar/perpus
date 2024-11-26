<?php

use Database\Seeders\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
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
use App\Http\Controllers\MasterShiftController;
use App\Http\Controllers\PenyimpanganController;


Route::get('/login', [loginController::class, 'loginForm'])->name('login');
Route::post('/login', [loginController::class, 'authenticate']);

Route::middleware(['auth'])->group(function () {
    //route dashboard
    Route::middleware(['userAksesMenu:6,1'])->group(function () {
        Route::get('/', [dashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/data-table1', [dashboardController::class, 'getTable1Data'])->name('data.table1');
        Route::get('/data-table2', [dashboardController::class, 'getTable2Data'])->name('data.table2');
        Route::get('/data-table1/details', [dashboardController::class, 'getTable1Details'])->name('data.table1.details');
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
        Route::get('/data/chart', [dashboardController::class, 'getChartData'])->name('data.chart'); //gakepake
        Route::get('/data/chart/hari', [dashboardController::class, 'getDataPerTanggal'])->name('data.perTanggal'); //gakepake
        Route::get('data/table1', [dashboardController::class, 'getTable1bData'])->name('data.table1b');

        //route rekap
        Route::get('/rekap', [rekapController::class, 'index'])->name('rekap.index');
        Route::get('/get-data', [rekapController::class, 'getData'])->name('rekap.getData');
        Route::post('/rekap/checkin', [rekapController::class, 'storeCheckin'])->name('rekap.storeCheckin');
        Route::post('/rekap/checkout', [rekapController::class, 'storeCheckout'])->name('rekap.storeCheckout');
        Route::get('/rekap/export', [RekapController::class, 'exportAbsensi'])->name('rekap.exportFilteredData');
        Route::post('/upload', [rekapController::class, 'upload'])->name('upload');
        Route::get('/get-attendance', [rekapController::class, 'getallattendance'])->name('rekap.attendance');
        Route::post('/update-data/{npk}/{tanggal}', [rekapController::class, 'updateData'])->name('edit.data');

        //route performa
        Route::get('/performa', [performaController::class, 'index'])->name('performa.index');
        Route::get('/performa/get-data', [performaController::class, 'getData'])->name('performa.getData');
        Route::post('/performa/storelog', [performaController::class, 'storeLogs'])->name('performa.storeLogs');
        Route::post('/performa/storeuserid', [performaController::class, 'storeUserId'])->name('performa.storeUserId');
        Route::get('/performa/export', [performaController::class, 'performaExport'])->name('performa.export');
        Route::get('/get-penyimpangan', [rekapController::class, 'getPenyimpangan'])->name('getPenyimpangan');
        Route::get('/get-cuti', [rekapController::class, 'getCuti'])->name('getCuti');

        Route::get('/user', [UsersController::class, 'index'])->name('karyawan.index');
        Route::post('/user', [UsersController::class, 'store']);
        Route::get('/user/detail/{npk}', [UsersController::class, 'detail']);
        Route::get('/user/edit/{npk}', [UsersController::class, 'edit']);
        Route::put('/user/update/{npk}', [UsersController::class, 'update']);
        Route::delete('/user/delete/{npk}', [UsersController::class, 'destroy']);
        Route::get('/karyawandata', [UsersController::class, 'karyawandata'])->name('karyawandata');
        Route::get('/departments/{divisionId}', [UsersController::class, 'getDepartments']);
        Route::get('/sections/{departmentId}', [UsersController::class, 'getSections']);

        //trash
        Route::resource('absensiControllerAjax', absensiController::class);
        Route::resource('absensiCoControllerAjax', absensiCoController::class);
        Route::resource('master-shift', MasterShiftController::class);



        Route::get('/users/export',  [UsersController::class, 'export'])->name('exportUsers');
    });

    Route::middleware(['userAksesMenu:6,1,2,9'])->group(function () {

        //route shift
        Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
        Route::get('shift-data', [shiftController::class, 'getData'])->name('shift.data');
        Route::get('/shift-history', [ShiftController::class, 'getShiftHistory'])->name('shift.history');
        Route::post('/store', [shiftController::class, 'store'])->name('shift.store');
        Route::post('/store2', [shiftController::class, 'store2'])->name('shift.store2');
        Route::get('/shift-data/{id}', [shiftController::class, 'edit'])->name('shift.edit');
        Route::put('/update/{id}', [shiftController::class, 'update'])->name('shift.update');
        Route::delete('/destroy/{id}', [shiftController::class, 'destroy'])->name('shift.destroy');
        Route::post('/fileupload', [shiftController::class, 'importProcess'])->name('shift.import');
        Route::post('/shift/update', [ShiftController::class, 'update'])->name('shift.update');
        Route::get('shift-data', [shiftController::class, 'getData'])->name('shift.data');
        Route::get('/shiftapi', [shiftController::class, 'shiftApi'])->name('shiftapi');
        Route::get('/export-data', [shiftController::class, 'exportData'])->name('exportData');
        Route::get('/exporttemplate', [shiftController::class, 'templateExport'])->name('exportTemplate');
        Route::get('/getKaryawan', [rekapController::class, 'getKaryawan'])->name('get.karyawan');


        //route data karyawan

    });
});

// Route::middleware(['auth', 'userAksesMenu:2'])->group(function () {
//     Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
//     Route::get('shift-data', [shiftController::class, 'getData'])->name('shift.data');
//     Route::get('/shift-history', [ShiftController::class, 'getShiftHistory']);
//     Route::post('/store', [shiftController::class, 'store'])->name('shift.store');
//     Route::post('/store2', [shiftController::class, 'store2'])->name('shift.store2');
//     Route::get('/shift-data/{id}', [shiftController::class, 'edit'])->name('shift.edit');
//     Route::put('/update/{id}', [shiftController::class, 'update'])->name('shift.update');
//     Route::delete('/destroy/{id}', [shiftController::class, 'destroy'])->name('shift.destroy');
//     Route::post('/fileupload', [shiftController::class, 'importProcess'])->name('shift.import');
//     Route::post('/shift/update', [ShiftController::class, 'update'])->name('shift.update');
//     Route::get('shift-data', [shiftController::class, 'getData'])->name('shift.data');
//     Route::get('/shift-history', [ShiftController::class, 'getShiftHistory']);


//     //route data karyawan
//     Route::get('/user', [UsersController::class, 'index'])->name('karyawan.index');
//     Route::post('/user', [UsersController::class, 'store']);
//     Route::get('/user/detail/{npk}', [UsersController::class, 'detail']);
//     Route::get('/user/edit/{npk}', [UsersController::class, 'edit']);
//     Route::put('/user/update/{npk}', [UsersController::class, 'update']);
//     Route::delete('/user/delete/{npk}', [UsersController::class, 'destroy']);
//     Route::get('/karyawandata', [UsersController::class, 'karyawandata'])->name('karyawandata');
//     Route::get('/departments/{divisionId}', [UsersController::class, 'getDepartments']);
//     Route::get('/sections/{departmentId}', [UsersController::class, 'getSections']);
// });

Route::get('/registerperformaapi123', [registController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/registerperformaapi123', [registController::class, 'register'])->name('register');
// Route::get('/sanctum/csrf-cookie', function (Request $request) {
//     return response()->json(['csrfToken' => csrf_token()]);
// });
Route::post('/logout', [loginController::class, 'logout'])->name('logout');


// wa gateway formulir

Route::get('/send-message-form', function () {
    return view('examples.wagateway');
})->name('send.message.form');
