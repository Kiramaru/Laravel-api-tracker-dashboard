<?php

use App\Http\Controllers\VisitController;
use App\Http\Controllers\PokemonController;

use Illuminate\Support\Facades\Route;

Route::post('/simple-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        session()->regenerate();
        return response()->json(['success' => true, 'redirect' => '/stats']);
    }

    return response()->json(['success' => false], 401);
});

Route::post('/visit/track', [VisitController::class, 'track']);
Route::get('/pokemons', [PokemonController::class, 'index']);
Route::get('/pokemons/{id}', [PokemonController::class, 'show']);

