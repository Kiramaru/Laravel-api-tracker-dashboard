<?php

namespace App\Http\Controllers;

use App\Contracts\StatsServiceInterface;
use App\Contracts\PokemonReadServiceInterface;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private StatsServiceInterface $statsService,
        private PokemonReadServiceInterface $pokemonService
    ) {}

    public function index()
    {
        $pokemons = $this->pokemonService->getAll();//Получение всех покемонов
        return view('stats', compact('pokemons'));
    }

    public function getData(): JsonResponse
    {
        try {
            $hourlyStats = $this->statsService->getHourlyStats();//Получение посещений
            $cityStats = $this->statsService->getCityStats();//Получение городов для статистики

            return response()->json([
                'success' => true,
                'hourly' => $hourlyStats->map(fn($item) => [
                    'hour' => (string) $item->hour,
                    'unique_visits' => (int) $item->unique_visits
                ])->values(),
                'cities' => $cityStats->map(fn($item) => [
                    'city' => $this->sanitizeCityName($item->city),
                    'count' => (int) $item->count
                ])->values(),
                'total' => (int) $this->statsService->getTotalVisits(),
                'unique_ips' => (int) $this->statsService->getUniqueIPsCount()
            ]);

        } catch (\Exception $e) {
            \Log::error('Stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'hourly' => [],
                'cities' => [],
                'total' => 0,
                'unique_ips' => 0,
                'error' => 'Unable to load statistics'
            ], 500);
        }
    }

    private function sanitizeCityName(?string $city): string //Обработка значения города
    {
        if (!$city || $city === 'Unknown') {//Если пусто или не известный
            return 'Неизвестный город';
        }

        $city = mb_convert_encoding($city, 'UTF-8', 'UTF-8');
        return preg_replace('/[^\p{L}\s\-\.]/u', '', $city) ?: 'Город';
    }
}