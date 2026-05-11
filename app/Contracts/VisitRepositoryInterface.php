<?php

namespace App\Contracts;

use App\Models\Visit;
use Illuminate\Support\Collection;

interface VisitRepositoryInterface //Интерфейс для работы с посещениями
{
    public function getHourlyStats(int $hours): Collection;
    public function getCityStats(int $limit): Collection;
    public function getTotalVisits(): int;
    public function getUniqueIPsCount(): int;
    public function create(array $data): Visit;
}
