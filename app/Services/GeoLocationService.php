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

        if ($this->isLocalIp($ip)) {
            return 'Local';
        }
        
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retries, 100)
                ->get($this->apiUrl . $ip);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'success') {
                    $city = $data['city'] ?? null;
                    if ($city) {
                        // Принудительная очистка строки
                        $city = mb_convert_encoding($city, 'UTF-8', 'auto');
                        $city = preg_replace('/[^\p{L}\s\-\.]/u', '', $city);
                        $city = trim($city);

                        if (strlen($city) > 0) {
                            return $city;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('GeoLocation error: ' . $e->getMessage());
        }

        return 'Unknown';
    
    }

    private function isLocalIp(string $ip): bool
    {
        return $ip === '127.0.0.1' ||
            $ip === '::1' ||
            str_starts_with($ip, '192.168.') ||
            str_starts_with($ip, '10.') ||
            str_starts_with($ip, '172.16.');
    }
}