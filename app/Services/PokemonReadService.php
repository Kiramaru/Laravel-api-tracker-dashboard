<?php

namespace App\Services;

use App\Contracts\PokemonReadServiceInterface;
use App\Contracts\PokemonApiInterface;
use App\Contracts\PokemonReadRepositoryInterface;
use App\Models\Pokemon;
use Illuminate\Support\Collection;


class PokemonReadService implements PokemonReadServiceInterface
{
    public function __construct(

        private PokemonReadRepositoryInterface $pokemonReadRepository,
    ) {
    }

    public function getAll(): Collection //Получение всех покемонов из БД
    {
        return $this->pokemonReadRepository->getAll();
    }

    public function findById(int $id): ?Pokemon //Поиск покемона по ID
    {
        return $this->pokemonReadRepository->findById($id);
    }

}
