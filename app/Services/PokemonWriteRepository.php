<?php

namespace App\Services;

use App\Contracts\PokemonWriteRepositoryInterface;
use App\Models\Pokemon;
use Illuminate\Support\Collection;

class PokemonWriteRepository implements PokemonWriteRepositoryInterface
{
    public function exists(int $pokemonId): bool //Существует ли покемон в БД
    {
        return Pokemon::where('pokemon_id', $pokemonId)->exists();
    }

    public function save(array $data): Pokemon //Добавление записи в БД
    {
        return Pokemon::create($data);
    }
}
