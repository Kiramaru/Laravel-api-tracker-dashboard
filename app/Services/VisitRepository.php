<?php

namespace App\Services;

use App\Contracts\VisitRepositoryInterface;
use App\Models\Visit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VisitRepository implements VisitRepositoryInterface
{
    public function getHourlyStats(int $hours): Collection//Получить почасовую статистику посещений
    {
        return Visit::select(
            DB::raw("datetime(created_at, 'start of hour') as hour"),
            DB::raw('COUNT(DISTINCT ip) as unique_visits')
        )
            ->whereNotNull('created_at')
            ->groupBy(DB::raw("datetime(created_at, 'start of hour')"))
            ->orderBy('hour', 'desc')
            ->limit($hours)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'hour' => $item->hour,
                    'unique_visits' => $item->unique_visits
                ];
            });
    }


    public function getCityStats(int $limit): Collection//Получить распределение по городам
    {
        $stats = Visit::select('city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->where('city', '!=', 'Неизвестный город')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();

        $stats = $stats->map(function ($item) {
            // Очищаем название города от возможных проблемных символов
            $city = $item->city;
            $city = preg_replace('/[^\p{L}\s\-]/u', '', $city);
            $city = mb_convert_encoding($city, 'UTF-8', 'UTF-8');

            return (object) [
                'city' => $city ?: 'Город',
                'count' => $item->count
            ];
        });

        // Если реальных данных нет, показываем демо-данные
        if ($stats->isEmpty()) {
            return collect([
                (object) ['city' => 'Москва', 'count' => 5],
                (object) ['city' => 'Санкт-Петербург', 'count' => 3],
                (object) ['city' => 'Новосибирск', 'count' => 2],
            ]);
        }

        return $stats;
    }

    public function getTotalVisits(): int //Получить общее количество посещений
    {
        return Visit::count();
    }


    public function getUniqueIPsCount(): int //Получить количество уникальных IP
    {
        return Visit::distinct('ip')->count('ip');
    }

    public function create(array $data): Visit //Создать запись о посещении
    {
        return Visit::create($data);
    }
}
