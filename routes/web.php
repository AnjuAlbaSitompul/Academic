<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'main']);
    Route::get('/logout', [LoginController::class, 'logout']);
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'main']);
    Route::get('/login', function () {
        return view('login');
    });
    Route::post('/login', [LoginController::class, 'login']);
});
