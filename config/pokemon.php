<?php

return [
    'api_url' => env('POKEMON_API_URL', 'https://pokeapi.co/api/v2/pokemon/'),
    'max_id' => env('POKEMON_MAX_ID', 1025),
    'api_timeout' => env('POKEMON_API_TIMEOUT', 60),
    'api_retries' => env('POKEMON_API_RETRIES', 3),
    'api_retry_delay' => env('POKEMON_API_RETRY_DELAY', 100),
];
