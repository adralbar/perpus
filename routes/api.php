<?php

use App\Http\Controllers\api\apiBroadcastController;
use App\Http\Controllers\api\apiGatewayController;
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
use App\Http\Controllers\api\MasterShiftApiController;

/*
|--------------------------------------------------------------------------
// API Routes
|--------------------------------------------------------------------------
// Here is where you can register API routes for your application. These
// routes are loaded by the RouteServiceProvider and all of them will
// be assigned to the "api" middleware group. Make something great!
//
*/

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set.']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::post('/login', [loginController::class, 'authenticate']);
});
Route::post('/uploadrekapbyapi', [rekapController::class, 'uploadapi'])->name('uploadapi');

Route::get('/karyawandataapi', [UsersController::class, 'karyawandata'])->name('karyawandataapi');

Route::get('/get-data', [rekapController::class, 'getDataApi'])->name('rekap.getData');
Route::get('/getRecapDataApi', [rekapController::class, 'getRecapDataApi'])->name('rekap.getRecapDataApi');

Route::get('/get-attendance', [rekapController::class, 'getallattendance'])->name('rekap.attendance');
Route::get('/master-shifts', [MasterShiftApiController::class, 'getMasterShift']);

Route::get('/check-late-and-absen', [apiBroadcastController::class, 'checkLateAndAbsent']);
Route::post('/wagateway', [apiGatewayController::class, 'sendMessage']);

Route::post('/send-message', [apiGatewayController::class, 'sendMessageFromRequest'])->name('api.sendMessage');
