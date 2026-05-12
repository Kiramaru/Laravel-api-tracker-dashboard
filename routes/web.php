<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

// Главная страница - редирект на логин
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    Route::get('/stats', [StatsController::class, 'index']);
    Route::get('/stats/data', [StatsController::class, 'getData']);
});