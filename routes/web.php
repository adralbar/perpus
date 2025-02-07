<?php

use Database\Seeders\users;
use Illuminate\Http\Request;
use App\Http\Controllers\readlist;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\rekapController;
use App\Http\Controllers\shiftController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\registController;
use App\Http\Controllers\uploadController;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\katalogController;
use App\Http\Controllers\performaController;
use App\Http\Controllers\absensiCoController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\rekapShiftController;
use App\Http\Controllers\MasterShiftController;
use App\Http\Controllers\daftarpinjamcontroller;
use App\Http\Controllers\PenyimpanganController;

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [loginController::class, 'loginForm'])->name('login');
Route::post('/login', [loginController::class, 'authenticate']);
Route::get('/regist', [registController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/regist', [registController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    //route dashboard
    Route::middleware(['userAksesMenu:1,2'])->group(function () {
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/katalog', [katalogController::class, 'index'])->name('katalog.index');
        Route::get('/katalog-data-dashboard', [katalogController::class, 'getBukuDashboard'])->name('katalog.getBuku-dashboard');
        Route::get('/katalog-data', [katalogController::class, 'getBuku'])->name('katalog.getBuku');
        Route::post('/katalog/store', [katalogController::class, 'storeDaftarBuku'])->name('storeDaftarBuku');
        Route::get('/buku/detail/{id}', [katalogController::class, 'showDetail'])->name('buku.detail');

        Route::delete('buku/{id}', [katalogController::class, 'destroy'])->name('buku.destroy');
        Route::post('buku/update/{id}', [katalogController::class, 'storeDaftarBuku'])->name('editDaftarBuku');
        Route::get('/buku/detail2/{id}', [katalogController::class, 'showDetail2'])->name('buku.detail');
        Route::get('/daftarpinjam/getbuku', [daftarpinjamcontroller::class, 'getBuku'])->name('daftarpinjam.getbuku');
        Route::get('/daftarpinjam', [daftarpinjamcontroller::class, 'index'])->name('daftarpinjam.index');
        Route::put('buku/update/{id}', [katalogController::class, 'updateDaftarBuku']);

        Route::put('/daftarpinjam/update/{id}', [DaftarPinjamController::class, 'updateStatus']);
    });
    Route::middleware(['userAksesMenu:2'])->group(function () {
        Route::get('/readlist', [readlist::class, 'index'])->name('readlist.index');
        Route::get('readlist/getbuku', [readlist::class, 'getBuku'])->name('readlist.getbuku');
        Route::post('/add-to-readlist', [katalogController::class, 'tambahKeReadlist'])->name('addToReadlist');
        Route::get('/check-readlist', [katalogController::class, 'checkReadlist'])->name('check-readlist');

        Route::post('/add-to-pinjam', [katalogController::class, 'tambahKePinjam'])->name('addTopinjam');
        Route::get('/check-pinjam', [katalogController::class, 'checkpinjam'])->name('check-pinjam');
    });
});

Route::post('/logout', [loginController::class, 'logout'])->name('logout');
