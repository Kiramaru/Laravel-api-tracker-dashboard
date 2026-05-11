<?php

namespace App\Services;

use App\Contracts\StatsServiceInterface;
use App\Contracts\VisitRepositoryInterface;
use Illuminate\Support\Collection;


class StatsService implements StatsServiceInterface
{

    public function __construct(

        private VisitRepositoryInterface $visitRepository

    ) {}

    public function getHourlyStats(int $hours = 48): Collection//Получить почасовую статистику посещений за последние 48 часов
    {
        return $this->visitRepository->getHourlyStats($hours);
    }

    public function getCityStats(int $limit = 20): Collection//Получить распределение по городам, ограничивая результат топ-20
    {
        return $this->visitRepository->getCityStats($limit);
    }

    public function getTotalVisits(): int//Получить общее количество посещений
    {
        return $this->visitRepository->getTotalVisits();
    }

    public function getUniqueIPsCount(): int//Получить количество уникальных IP
    {
        return $this->visitRepository->getUniqueIPsCount();
    }
}
