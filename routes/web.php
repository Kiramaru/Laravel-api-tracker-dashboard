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

// Тестовый логин без CSRF
Route::post('/test-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        Auth::login(Auth::user());
        return redirect('/stats');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
});

// ВРЕМЕННЫЙ МАРШРУТ ДЛЯ ОБХОДА ПРОБЛЕМ С СЕССИЯМИ
Route::get('/force-login', function () {
    $user = \App\Models\User::where('email', 'kiramaru@example.com')->first();

    if ($user) {
        Auth::login($user);
        return redirect('/stats');
    }

    return 'User not found. Please check database.';
});

Route::get('/debug-stats', function () {
    $statsService = app(\App\Contracts\StatsServiceInterface::class);
    return response()->json([
        'hourly' => $statsService->getHourlyStats(),
        'cities' => $statsService->getCityStats(),
        'total' => $statsService->getTotalVisits(),
        'unique_ips' => $statsService->getUniqueIPsCount()
    ]);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware('auth')->group(function () {
    Route::get('/stats', [StatsController::class, 'index']);
    Route::get('/stats/data', [StatsController::class, 'getData']);
});