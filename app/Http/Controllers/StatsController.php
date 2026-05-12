<?php

namespace App\Http\Controllers;

use App\Contracts\StatsServiceInterface;
use App\Contracts\PokemonReadServiceInterface;

class StatsController extends Controller
{
    public function __construct(

        private StatsServiceInterface $statsService,
        private PokemonReadServiceInterface $pokemonService

    ) {}

    public function index()//Отображение страницы со статистикой
    {

        $pokemons = $this->pokemonService->getAll();

        return view('stats', compact('pokemons'));
    }

    public function getData()//Получение данных для графиков
    {
        try {
            // Получаем данные и гарантируем, что они в правильном формате
            $hourlyStats = $this->statsService->getHourlyStats();
            $cityStats = $this->statsService->getCityStats();

            // Преобразуем в массив с гарантией правильной кодировки
            $hourly = [];
            foreach ($hourlyStats as $item) {
                $hourly[] = [
                    'hour' => (string) $item->hour,
                    'unique_visits' => (int) $item->unique_visits
                ];
            }

            $cities = [];
            foreach ($cityStats as $item) {
                $cityName = $item->city ?? 'Unknown';
                // Очищаем от потенциально проблемных символов
                $cityName = mb_convert_encoding($cityName, 'UTF-8', 'UTF-8');
                $cities[] = [
                    'city' => $cityName,
                    'count' => (int) $item->count
                ];
            }

            // Если данных нет, добавляем заглушку для отображения
            if (empty($hourly)) {
                $hourly[] = [
                    'hour' => now()->format('Y-m-d H:00'),
                    'unique_visits' => 0
                ];
            }

            if (empty($cities)) {
                $cities[] = [
                    'city' => 'Нет данных',
                    'count' => 1
                ];
            }

            $responseData = [
                'success' => true,
                'hourly' => $hourly,
                'cities' => $cities,
                'total' => (int) $this->statsService->getTotalVisits(),
                'unique_ips' => (int) $this->statsService->getUniqueIPsCount()
            ];

            return response()->json($responseData)
                ->header('Content-Type', 'application/json; charset=utf-8');

        } catch (\Exception $e) {
            \Log::error('Stats error: ' . $e->getMessage());

            // Возвращаем пустые данные в правильном формате
            return response()->json([
                'success' => false,
                'hourly' => [
                    ['hour' => now()->format('Y-m-d H:00'), 'unique_visits' => 0]
                ],
                'cities' => [
                    ['city' => 'Ошибка загрузки', 'count' => 1]
                ],
                'total' => 0,
                'unique_ips' => 0,
                'error' => $e->getMessage()
            ])->header('Content-Type', 'application/json; charset=utf-8');
        }
    }
}
