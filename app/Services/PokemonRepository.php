<?php

namespace App\Services;

use App\Contracts\PokemonRepositoryInterface;
use App\Models\Pokemon;
use Illuminate\Support\Collection;

class PokemonRepository implements PokemonRepositoryInterface
{
    public function exists(int $pokemonId): bool //Существует ли покемон в БД
    {
        return Pokemon::where('pokemon_id', $pokemonId)->exists();
    }

    public function save(array $data): Pokemon //Добавление записи в БД
    {
        return Pokemon::create($data);
    }
    public function getAll(): Collection //Получить всех покемонов
    {
        return Pokemon::all();
    }

    public function findById(int $id): ?Pokemon //Найти покемона по id 
    {
        return Pokemon::find($id);
    }
}
