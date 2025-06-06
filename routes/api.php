<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\PemesananApiController;
use App\Http\Controllers\Api\TopUpController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/jadwal-keberangkatan', [JadwalController::class, 'index']);
Route::get('/jadwal-keberangkatan/{id}', [JadwalController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pemesanan', [PemesananApiController::class, 'store']);
    Route::get('/pemesanan/riwayat', [PemesananApiController::class, 'riwayatPemesanan']);
    Route::get('/pemesanan/{pemesanan}', [PemesananApiController::class, 'showPemesananDetail']);
    Route::post('/topup/request', [TopUpController::class, 'requestTopUp']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
