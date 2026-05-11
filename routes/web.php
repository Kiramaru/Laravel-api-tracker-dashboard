<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;

Route::get('/debug-files', function() {
    $files = scandir('/var/www/html');
    return response()->json([
        'files' => $files,
        'has_composer_json' => file_exists('/var/www/html/composer.json'),
        'has_composer_lock' => file_exists('/var/www/html/composer.lock'),
        'pwd' => getcwd()
    ]);
});

Route::get('/', function () {
    return redirect('/stats');
});

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return 'Cache cleared: ' . Artisan::output();
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');//Форма ввода

Route::post('/login', [AuthController::class, 'login']);//Обработка формы ввода

Route::post('/logout', [AuthController::class, 'logout']);//Выход из системы

Route::middleware('auth')->group(function () { //Проверка авторизации для доступа к статистике

    Route::get('/stats', [StatsController::class, 'index']);//Показ статистики

    Route::get('/stats/data', [StatsController::class, 'getData']);//Получение данных для графиков
});
