<?php


namespace App\Contracts;

use Illuminate\Support\Collection;
use App\Models\Pokemon;

interface PokemonReadServiceInterface //Интерфейс для получения данных о покемонах, который будет использоваться в контроллерах и командах
{
    public function getAll(): Collection;
    public function findById(int $id): ?Pokemon;
}
