<?php

namespace App\Contracts;

interface PokemonApiInterface //Интерфейс для Api
{
    public function fetchRandom(): array;
}
