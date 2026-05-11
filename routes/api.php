<?php

use App\Http\Controllers\VisitController;
use App\Http\Controllers\PokemonController;

use Illuminate\Support\Facades\Route;

Route::post('/visit/track', [VisitController::class, 'track']);
Route::get('/pokemons', [PokemonController::class, 'index']);
Route::get('/pokemons/{id}', [PokemonController::class, 'show']);

