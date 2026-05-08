<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface StatsServiceInterface
{
    public function getHourlyStats(int $hours = 48): Collection;
    public function getCityStats(int $limit = 20): Collection;
    public function getTotalVisits(): int;
    public function getUniqueIPsCount(): int;
}
