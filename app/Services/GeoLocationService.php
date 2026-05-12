<?php

namespace App\Services;

use App\Contracts\GeoLocationServiceInterface;
use Illuminate\Support\Facades\Http;

class GeoLocationService implements GeoLocationServiceInterface
{
    private string $apiUrl;
    private int $timeout;
    private int $retries;

    public function __construct()
    {
        $this->apiUrl = config('geo.api_url', 'http://ip-api.com/json/');
        $this->timeout = config('geo.timeout', 5);
        $this->retries = config('geo.retries', 1);
    }

    public function getCityByIp(string $ip): ?string //Получение города по ip
    {

        try {
            $response = Http::timeout($this->timeout)//Запрос на получение города
                ->retry($this->retries, 100)
                ->get($this->apiUrl . $ip);

            if ($response->successful() && $response->json('status') === 'success') {//Если успешно
                $city = $response->json('city');
                if ($city && $city !== '') {
                    $city = mb_convert_encoding($city, 'UTF-8', 'auto');//Кодировка
                    return $city;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('GeoLocationService error: ' . $e->getMessage(), [
                'ip' => $ip,
                'api_url' => $this->apiUrl
            ]);
        }

        return 'Неизвестный город';
    }
}