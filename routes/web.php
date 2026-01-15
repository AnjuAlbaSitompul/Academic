<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'main']);
    Route::get('/logout', [LoginController::class, 'logout']);

    // Siswa Routes
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/data/{kelasId?}', [SiswaController::class, 'fetchSiswa']);
    Route::post('/siswa', [SiswaController::class, 'inputSiswa']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'deleteSiswa']);
    Route::put('/siswa/{id}', [SiswaController::class, 'updateSiswa']);

    // Guru Routes
    Route::get('/guru', [GuruController::class, 'index']);
    Route::get('/guru/data/{kelasId?}', [GuruController::class, 'fetchGuru']);
    Route::post('/guru', [GuruController::class, 'inputGuru']);
    Route::delete('/guru/{id}', [GuruController::class, 'deleteGuru']);
    Route::put('/guru/{id}', [GuruController::class, 'updateGuru']);

    // Kelas Routes
    Route::get('/kelas', function () {
        return view('kelas.index');
    });
    Route::post('/kelas', [KelasController::class, 'inputKelas']);
    Route::get('/kelas/data', [KelasController::class, 'fetchKelas']);
    Route::delete('/kelas/{id}', [KelasController::class, 'deleteKelas']);
    Route::put('/kelas/{id}', [KelasController::class, 'updateKelas']);


    // nilai-siswa routes
    Route::post('/nilai-siswa/upload', [NilaiController::class, 'upload']);
    Route::get('/nilai-siswa', [NilaiController::class, 'index']);
    Route::post('/nilai-siswa', [NilaiController::class, 'store']);
    Route::put('/nilai-siswa/{id}', [NilaiController::class, 'update']);
    Route::delete('/nilai-siswa/{id}', [NilaiController::class, 'destroy']);
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'main']);
    Route::get('/login', function () {
        return view('login');
    });
    Route::post('/login', [LoginController::class, 'login']);
});
