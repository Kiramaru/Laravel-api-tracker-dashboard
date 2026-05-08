<?php

namespace App\Http\Controllers;

use App\Contracts\PokemonRepositoryInterface;
use App\Contracts\StatsServiceInterface;

class StatsController extends Controller
{
    public function __construct(

        private StatsServiceInterface $statsService,
        private PokemonRepositoryInterface $pokemonRepository

    ) {}

    public function index()
    {

        $pokemons = $this->pokemonRepository->getAll();

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
