<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');//Форма ввода

Route::post('/login', [AuthController::class, 'login']);//Обработка формы ввода

Route::post('/logout', [AuthController::class, 'logout']);//Выход из системы

Route::middleware('auth')->group(function () { //Проверка авторизации для доступа к статистике

    Route::get('/stats', [StatsController::class, 'index']);//Показ статистики

    Route::get('/stats/data', [StatsController::class, 'getData']);//Получение данных для графиков
});
