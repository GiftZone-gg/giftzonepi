<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login-action', [AuthController::class, 'login'])->name('login.auth');
Route::post('/register-action', [AuthController::class, 'register'])->name('register.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');