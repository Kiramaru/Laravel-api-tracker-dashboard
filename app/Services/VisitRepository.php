<?php

namespace App\Services;

use App\Contracts\VisitRepositoryInterface;
use App\Models\Visit;

class VisitRepository implements VisitRepositoryInterface
{
    public function create(array $data): Visit //Создание посещения
    {
        return Visit::create($data);
    }
}
