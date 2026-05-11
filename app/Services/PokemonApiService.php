<?php

namespace App\Services;

use App\Contracts\PokemonApiInterface;
use Illuminate\Support\Facades\Http;

class PokemonApiService implements PokemonApiInterface
{
    public function __construct(

        private string $baseUrl,
        private int $maxId,
        private int $timeout = 60,
        private int $retries = 3,
        private int $retryDelay = 100

    ) {}

    public function fetchRandom(): array
    {
        $randomId = rand(1, $this->maxId); //Рандомный id от 1 до максимального

        $response = Http::timeout($this->timeout)
            ->retry($this->retries, $this->retryDelay)
            ->get($this->baseUrl . $randomId); //Отправка запроса

        if (!$response->successful()) { //Если не прошел

            throw new \Exception("Failed to fetch pokemon from API");
        }

        $data = $response->json();//Ответ от запроса

        return [

            'pokemon_id' => $data['id'],
            'name' => $data['name'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'types' => $data['types'],
            'image_url' => $data['sprites']['front_default'],
            'abilities' => $this->extractAbilities($data['abilities']),
        ];
    }

    private function extractAbilities(array $abilities): string //Функция для возврата абилок записанных в строку через ,
    {
        $abilityNames = [];

        foreach ($abilities as $abilityData) {

            $abilityNames[] = $abilityData['ability']['name'];//Заполнение массива с абилками
        }
        return implode(', ', $abilityNames);//Возврат строки
    }
}
