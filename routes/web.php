<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjahitController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KainController;

// Jika seseorang membuka halaman depan
Route::get('/', function () {
    return redirect('/login');
});
// Menampilkan form login
Route::get('/login', [AuthController::class, 'login'])->name('login');
// Memproses pencocokan data login
Route::post('/login', [AuthController::class, 'authenticate']);

Route::middleware('auth')->group(function () {
    // Jalur untuk menampilkan halaman form tambah pesanan
    Route::get('/pesanan/tambah', [PesananController::class, 'create']);

    // Jalur untuk memproses data saat tombol "Simpan" ditekan
    Route::post('/pesanan/simpan', [PesananController::class, 'store']);

    // Jalur untuk menampilkan daftar semua pesanan
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/penjahit/dashboard', [PenjahitController::class, 'indexPenjahit']);
    // Route untuk mengubah status pesanan & penugasan penjahit
    Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus']);
    Route::patch('/pesanan/{id}/tugaskan', [PesananController::class, 'tugaskan']);
    // Route untuk kwitansi
    Route::get('/kwitansi', [PesananController::class, 'kwitansiIndex']);
    Route::get('/pesanan/{id}/kwitansi/dp', [PesananController::class, 'cetakDp']);
    Route::get('/pesanan/{id}/kwitansi/pelunasan', [PesananController::class, 'cetakPelunasan']);

    // Route untuk Laporan (Keuangan, Pesanan, Kinerja Penjahit)
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak']);

    // Route untuk Inventori Kain
    Route::get('/kain', [KainController::class, 'index']);
    Route::post('/kain', [KainController::class, 'store']);
    Route::put('/kain/{id}', [KainController::class, 'update']);
    Route::delete('/kain/{id}', [KainController::class, 'destroy']);

    // Route untuk menghapus pesanan
    Route::delete('/pesanan/{id}', [PesananController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



