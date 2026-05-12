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
        if ($ip === '127.0.0.1' || str_starts_with($ip, '10.') || str_starts_with($ip, '172.')) {
            return 'Тестовый город';
        }
        
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retries, 100)
                ->get($this->apiUrl . $ip);//Запрос для получения города

            if ($response->successful() && $response->json('status') === 'success') {
                return $response->json('city');//Если запрос прошел успешно, то возвращаем город
            }
        } catch (\Exception $e) {//Ошибка
            \Log::warning('GeoLocationService error: ' . $e->getMessage(), [
                'ip' => $ip,
                'api_url' => $this->apiUrl
            ]);
        }

        return 'Неизвестный город';
    }
}