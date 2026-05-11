<?php

namespace App\Services;

use App\Contracts\PokemonReadRepositoryInterface;
use App\Models\Pokemon;
use Illuminate\Support\Collection;

class PokemonReadRepository implements PokemonReadRepositoryInterface
{
    
    public function getAll(): Collection //Получить всех покемонов
    {
        return Pokemon::all();
    }

    public function findById(int $id): ?Pokemon //Найти покемона по id 
    {
        return Pokemon::find($id);
    }
}
