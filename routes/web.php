<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'welcome']);

Route::get('/home', [FlightController::class, 'home']);

Route::get('/insert-model', [FlightController::class, 'insertModel']);

Route::get('display-model', [FlightController::class, 'displayModel']);

Route::get('/formular', [FlightController::class, 'formular']);
Route::post('/formular', [FlightController::class, 'post']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');