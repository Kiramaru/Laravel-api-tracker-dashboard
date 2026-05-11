<?php

namespace App\Http\Controllers;

use App\Contracts\StatsServiceInterface;
use App\Contracts\PokemonReadServiceInterface;

class StatsController extends Controller
{
    public function __construct(

        private StatsServiceInterface $statsService,
        private PokemonReadServiceInterface $pokemonService

    ) {}

    public function index()//Отображение страницы со статистикой
    {

        $pokemons = $this->pokemonService->getAll();

        return view('stats', compact('pokemons'));
    }

    public function getData()//Получение данных для графиков
    {
        return response()->json([
            'hourly' => $this->statsService->getHourlyStats(),
            'cities' => $this->statsService->getCityStats(),
            'total' => $this->statsService->getTotalVisits(),
            'unique_ips' => $this->statsService->getUniqueIPsCount()
        ]);
    }
}
