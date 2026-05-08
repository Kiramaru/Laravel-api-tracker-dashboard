<?php

namespace App\Contracts;
use Illuminate\Support\Collection;
use App\Models\Pokemon;

interface PokemonRepositoryInterface //Интерфейс для работы с БД
{
    public function exists(int $pokemonId): bool;
    public function save(array $data): Pokemon;
    public function getAll(): Collection;
    public function findById(int $id): ?Pokemon;
}
