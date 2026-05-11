<?php

namespace App\Console\Commands;

use App\Contracts\PokemonServiceInterface;
use App\Exceptions\PokemonAlreadyExistsException;
use Illuminate\Console\Command;


class FetchPokemonData extends Command
{
    protected $signature = 'pokemon:fetch';
    protected $description = 'Fetch random pokemon data from PokeAPI';

    public function __construct(

        private PokemonServiceInterface $pokemonService

        ) {

        parent::__construct();//Вызов конструктора родительского класса
    }

    public function handle(): int
    {
        try {

            $pokemon = $this->pokemonService->fetchAndSaveRandom();//Получение данных из API и сохранение в БД

            $this->info("New pokemon saved: " . $pokemon->name);
            return 0;


        } catch (PokemonAlreadyExistsException $e) {//Если покемон уже существует, выводим сообщение и завершаем команду без ошибки
            $this->info($e->getMessage());
            return 0;

        } catch (\Exception $e) {

            $this->error("Error: " . $e->getMessage());//Вывод ошибки, если что-то пошло не так
            return 1;
        }

    }
}
