<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservationController;

Route::get('/', [FlightController::class, 'welcome']);

Route::get('/home', [FlightController::class, 'home'])->name('home');

Route::get('/insert-model', [FlightController::class, 'insertModel']);

Route::get('display-model', [FlightController::class, 'displayModel']);

Route::get('/formular', [FlightController::class, 'formular']);
Route::post('/formular', [FlightController::class, 'post']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/flights', [AdminController::class, 'flights'])->name('admin.flights');
    Route::delete('/flights/{id}', [AdminController::class, 'deleteFlight'])->name('admin.flights.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-reservations', [ReservationController::class, 'myReservations'])->name('my-reservations');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');

    Route::get('/book-flight/{flight}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/book-flight', [ReservationController::class, 'store'])->name('reservations.store');
});