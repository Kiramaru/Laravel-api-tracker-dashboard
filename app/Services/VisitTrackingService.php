<?php

namespace App\Services;

use App\Contracts\VisitTrackingServiceInterface;
use App\Contracts\VisitRepositoryInterface;
use App\Contracts\GeoLocationServiceInterface;

class VisitTrackingService implements VisitTrackingServiceInterface
{
    public function __construct(
        private VisitRepositoryInterface $visitRepository,
        private GeoLocationServiceInterface $geoLocationService
    ) {
    }

    public function trackVisit(array $validatedData, string $ip): array
    {

        $realIp = $this->getRealIp();

        // Логируем для отладки
        \Log::info('Real IP detected', [
            'provided_ip' => $ip,
            'real_ip' => $realIp,
            'all_headers' => request()->headers->all()
        ]);

        $city = $this->geoLocationService->getCityByIp($realIp);

        $visit = $this->visitRepository->create([
            'ip' => $realIp,
            'city' => $city,
            'device' => $validatedData['device'] ?? null,
            'browser' => $validatedData['browser'] ?? null,
            'page_url' => $validatedData['page_url'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Visit tracked',
            'visit_id' => $visit->id,
            'client_ip' => $realIp
        ];


    }
    private function getRealIp(): string
    {
        // Проверяем заголовки с реальным IP
        $headers = [
            'CF-Connecting-IP',     // Cloudflare
            'X-Forwarded-For',       // Стандартный прокси
            'X-Real-IP',             // Nginx
            'X-Forwarded',           // Альтернативный
            'Forwarded-For',         // Альтернативный
            'Forwarded'              // Альтернативный
        ];

        foreach ($headers as $header) {
            if ($value = request()->header($header)) {
                // X-Forwarded-For может содержать несколько IP
                if (str_contains($value, ',')) {
                    $ips = explode(',', $value);
                    $value = trim($ips[0]);
                }

                if (filter_var($value, FILTER_VALIDATE_IP)) {
                    return $value;
                }
            }
        }

        // Если нет заголовков - берем стандартный IP
        return request()->ip();
    }
}