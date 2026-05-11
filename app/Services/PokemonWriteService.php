<?php

namespace App\Services;

use App\Contracts\PokemonServiceInterface;
use App\Contracts\PokemonApiInterface;
use App\Contracts\PokemonWriteRepositoryInterface;
use App\Models\Pokemon;
use App\Exceptions\PokemonAlreadyExistsException;


class PokemonWriteService implements PokemonServiceInterface
{
    public function __construct(

        private PokemonApiInterface $pokemonApi,
        private PokemonWriteRepositoryInterface $pokemonWriteRepository
    ) {
    }
    public function fetchAndSaveRandom(): Pokemon //Получение случайного покемона из API и сохранение его в БД
    {
        $pokemonData = $this->pokemonApi->fetchRandom();

        if ($this->pokemonWriteRepository->exists($pokemonData['pokemon_id'])) {//Проверка на существование покемона в БД

            throw new PokemonAlreadyExistsException($pokemonData['name']);
        }

        return $this->pokemonWriteRepository->save($pokemonData);
    }
}
