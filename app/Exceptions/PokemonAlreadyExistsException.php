<?php

namespace App\Exceptions;

use Exception;

class PokemonAlreadyExistsException extends Exception
{
    public function __construct(string $pokemonName)//Конструктор для передачи имени покемона, который уже существует
    {
        parent::__construct("Pokemon already exists: " . $pokemonName);
    }
}
