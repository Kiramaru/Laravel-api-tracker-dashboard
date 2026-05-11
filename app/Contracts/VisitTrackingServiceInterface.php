<?php

namespace App\Contracts;

interface VisitTrackingServiceInterface
{
    public function trackVisit(array $validatedData, string $ip): array;
}