<?php

namespace App\Services;

use App\Contracts\StatsServiceInterface;
use App\Models\Visit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatsService implements StatsServiceInterface
{
    
    public function getHourlyStats(int $hours = 48): Collection//Получить почасовую статистику посещений
    {
        return Visit::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as hour'),
            DB::raw('COUNT(DISTINCT ip) as unique_visits')
        )
            ->groupBy('hour')
            ->orderBy('hour', 'desc')
            ->limit($hours)
            ->get();
    }

    
    public function getCityStats(int $limit = 20): Collection//Получить распределение по городам
    {
        return Visit::select('city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTotalVisits(): int //Получить общее количество посещений
    {
        return Visit::count();
    }

    
    public function getUniqueIPsCount(): int //Получить количество уникальных IP
    {
        return Visit::distinct('ip')->count('ip');
    }
}
