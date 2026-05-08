<?php

namespace App\Http\Controllers;

use App\Contracts\PokemonRepositoryInterface;

class PokemonController extends Controller
{
    public function __construct(

        private PokemonRepositoryInterface $pokemonRepository

    ) {}

    public function index()
    {
        $pokemons = $this->pokemonRepository->getAll();

        return response()->json([
            'success' => true,
            'data' => $pokemons,
            'count' => $pokemons->count()
        ]);
    }

    public function show($id)
    {
        $pokemon = $this->pokemonRepository->findById($id);

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
