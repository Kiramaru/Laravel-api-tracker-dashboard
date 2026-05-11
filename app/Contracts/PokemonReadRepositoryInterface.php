<?php


namespace App\Contracts;

use Illuminate\Support\Collection;
use App\Models\Pokemon;

interface PokemonReadRepositoryInterface {
    public function getAll(): Collection;
    public function findById(int $id): ?Pokemon;
}
