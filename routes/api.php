<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\KoleksiPribadiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UlasanController;
use App\Models\KoleksiPribadi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route Login And Register
Route::prefix('/users')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registrasi', [AuthController::class, 'registrasi']);

    Route::group(['middleware' => 'auth:sanctum'], function() {

        Route::get('/buku', [BukuController::class, 'tampilAllBuku']);
        Route::get('/buku/new', [BukuController::class, 'tampilAllBukuNew']);
        Route::post('/buku/search', [BukuController::class, 'searchBuku']);
        Route::get('/buku/{id}', [BukuController::class, 'tampilBuku']);
        Route::delete('/buku/{id}', [BukuController::class, 'destroyBuku']);
        Route::get('/popular/buku', [BukuController::class, 'getBukuPopuler']);

        Route::get('/pinjam', [PeminjamanController::class, 'tampilAllPeminjaman']);
        Route::get('/pinjam/{id}', [PeminjamanController::class, 'tampilPeminjaman']);
        Route::post('/pinjam', [PeminjamanController::class, 'addPeminjaman']);
        Route::delete('/pinjam/{id}', [PeminjamanController::class, 'destroyPinjam']);
        
        Route::get('/ulasan/{id}', [UlasanController::class, 'tampilAllUlasan']);
        Route::post('/ulasan', [UlasanController::class, 'addUlasan']);
        
        Route::get('/koleksi/{id}', [KoleksiPribadiController::class, 'tampilUlasanBy']);
        Route::delete('/koleksi/{id}', [KoleksiPribadiController::class, 'deleteKoleksi']);
        Route::post('/koleksi', [KoleksiPribadiController::class, 'addKoleksi']);

        Route::get('/kategori', [KategoriBukuController::class, 'getAllKategori']);
    });
});

Route::prefix('/admin')->middleware('auth:sanctum')->group(function() {
    Route::get('/buku', [BukuController::class, 'tampilAllBuku']);
    Route::post('/buku', [BukuController::class, 'addBuku']);
    Route::put('/buku/{id}', [BukuController::class, 'updateBuku']);

    Route::get('/kategori', [KategoriBukuController::class, 'getAllKategori']);
    Route::post('/kategori', [KategoriBukuController::class, 'createKategori']);
});
