<?php

namespace App\Contracts;
use App\Models\Pokemon;
use Illuminate\Support\Collection;
interface PokemonServiceInterface {

    public function fetchAndSaveRandom(): Pokemon;
}
