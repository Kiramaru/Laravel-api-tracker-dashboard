<?php

namespace App\Contracts;

use App\Models\Visit;

interface VisitRepositoryInterface //Интерфейс для работы с посещениями
{
    public function create(array $data): Visit;
}
