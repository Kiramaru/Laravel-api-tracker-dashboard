<?php

namespace App\Console\Commands;

use App\Contracts\PokemonApiInterface;
use App\Contracts\PokemonRepositoryInterface;
use Illuminate\Console\Command;


class FetchPokemonData extends Command
{
    protected $signature = 'pokemon:fetch';
    protected $description = 'Fetch random pokemon data from PokeAPI';

    public function __construct(

        private PokemonApiInterface $pokemonApi,
        private PokemonRepositoryInterface $pokemonRepository

        ) {

        parent::__construct();//Вызов конструктора родительского класса
    }

    public function handle(): int
    {
        try {
            
            $pokemonData = $this->pokemonApi->fetchRandom();//Получение данных из API

            
            if ($this->pokemonRepository->exists($pokemonData['pokemon_id'])) {//Проверква присутствует ли в БД этот покемон

                $this->info("Pokemon already exists: " . $pokemonData['name']);
                return 0;
            }

            
            $this->pokemonRepository->save($pokemonData);//Запись покемона в БД

            $this->info("New pokemon saved: " . $pokemonData['name']);
            return 0;

        }
        catch (\Exception $e) {

            $this->error("Error " . $e->getMessage());
            return 1;
        }
    }
}
