<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::post('/login', [LoginController::class, 'login'])->name("login");
Route::post('/register/user', [LoginController::class, 'register'])->name("register");
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    // Aquí van las rutas que requieren autenticación
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
});