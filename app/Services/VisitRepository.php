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
            ->map(fn($item) => (object) [
                'hour' => $item->hour,
                'unique_visits' => (int) $item->unique_visits
            ]);
    }


    public function getCityStats(int $limit): Collection//Получить распределение по городам
    {
        $stats = Visit::select('city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->where('city', '!=', 'Unknown')
            ->where('city', '!=', 'Неизвестный город')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();

        return $stats->map(fn($item) => (object) [
            'city' => $this->sanitizeCityName($item->city),
            'count' => (int) $item->count
        ]);
    }

    private function sanitizeCityName(string $city): string //Изменить кодировку у города и почистить от лишних символов
    {
        $city = preg_replace('/[^\p{L}\s\-]/u', '', $city);
        return mb_convert_encoding($city, 'UTF-8', 'UTF-8') ?: 'Город';
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
