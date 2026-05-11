<?php

namespace App\Contracts;

interface GeoLocationServiceInterface
{
    public function getCityByIp(string $ip): ?string;
}