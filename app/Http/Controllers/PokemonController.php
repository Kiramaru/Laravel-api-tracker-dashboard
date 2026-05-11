<?php

namespace App\Http\Controllers;

use App\Contracts\PokemonReadServiceInterface;

class PokemonController extends Controller
{
    public function __construct(

        private PokemonReadServiceInterface $pokemonService

    ) {}

    public function index()//Получение всех покемонов
    {
        $pokemons = $this->pokemonService->getAll();

        return response()->json([
            'success' => true,
            'data' => $pokemons,
            'count' => $pokemons->count()
        ]);
    }

    public function show($id)//Получение покемона по ID
    {
        $pokemon = $this->pokemonService->findById($id);

        if (!$pokemon) {
            return response()->json([
                'success' => false,
                'message' => 'Pokemon not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pokemon
        ]);
    }
}
