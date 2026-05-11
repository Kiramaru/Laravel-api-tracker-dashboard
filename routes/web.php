<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function() {
    try {
        Artisan::call('optimize:clear');
        return response()->json([
            'success' => true,
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');//Форма ввода

Route::post('/login', [AuthController::class, 'login']);//Обработка формы ввода

Route::post('/logout', [AuthController::class, 'logout']);//Выход из системы

Route::middleware('auth')->group(function () { //Проверка авторизации для доступа к статистике

    Route::get('/stats', [StatsController::class, 'index']);//Показ статистики

    Route::get('/stats/data', [StatsController::class, 'getData']);//Получение данных для графиков
});
