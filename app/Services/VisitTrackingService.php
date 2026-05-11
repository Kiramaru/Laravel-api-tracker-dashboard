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

        $visit = $this->visitRepository->create([
            'ip' => $ip,
            'city' => null,
            'device' => $validatedData['device'] ?? null,
            'browser' => $validatedData['browser'] ?? null,
            'page_url' => $validatedData['page_url'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Visit tracked',
            'visit_id' => $visit->id
        ];
    }
}