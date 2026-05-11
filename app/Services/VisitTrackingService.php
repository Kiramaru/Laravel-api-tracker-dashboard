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

        try {
            // Получаем город по IP
            $city = $this->geoLocationService->getCityByIp($ip);

            // Создаём запись о посещении
            $visit = $this->visitRepository->create([
                'ip' => $ip,
                'city' => $city,
                'device' => $validatedData['device'] ?? null,
                'browser' => $validatedData['browser'] ?? null,
                'page_url' => $validatedData['page_url'] ?? null,
            ]);

            Log::info('Visit saved', ['visit_id' => $visit->id]);

            return [
                'success' => true,
                'message' => 'Visit tracked',
                'visit_id' => $visit->id
            ];
        } catch (\Exception $e) {
            Log::error('Visit tracking failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
                ]);

            return [
                'success' => false,
                'message' => 'Failed to track visit: ' . $e->getMessage()
            ];
        }
    }
}