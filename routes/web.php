<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;

// Главная страница - редирект на логин
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/test-php', function () {
    return response()->json(['status' => 'PHP works']);
});

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return 'Cache cleared: ' . Artisan::output();
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/stats', [StatsController::class, 'index']);
    Route::get('/stats/data', [StatsController::class, 'getData']);
});