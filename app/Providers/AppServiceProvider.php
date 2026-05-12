<?php

namespace App\Providers;

use App\Contracts\PokemonReadRepositoryInterface;
use App\Contracts\PokemonReadServiceInterface;
use App\Contracts\PokemonWriteRepositoryInterface;
use App\Contracts\PokemonServiceInterface;
use App\Contracts\StatsServiceInterface;
use App\Contracts\VisitRepositoryInterface;
use App\Contracts\GeoLocationServiceInterface;
use App\Contracts\VisitTrackingServiceInterface;
use App\Contracts\PokemonApiInterface;

use App\Services\PokemonReadRepository;
use App\Services\PokemonReadService;
use App\Services\PokemonWriteRepository;
use App\Services\PokemonWriteService;
use App\Services\StatsService;
use App\Services\VisitRepository;
use App\Services\GeoLocationService;
use App\Services\VisitTrackingService;
use App\Services\PokemonApiService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Репозитории
        $this->app->bind(PokemonReadRepositoryInterface::class, PokemonReadRepository::class);
        $this->app->bind(PokemonWriteRepositoryInterface::class, PokemonWriteRepository::class);
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);

        // Сервисы
        $this->app->bind(PokemonReadServiceInterface::class, PokemonReadService::class);
        $this->app->bind(PokemonServiceInterface::class, PokemonWriteService::class);
        $this->app->bind(StatsServiceInterface::class, StatsService::class);
        $this->app->bind(GeoLocationServiceInterface::class, GeoLocationService::class);
        $this->app->bind(VisitTrackingServiceInterface::class, VisitTrackingService::class);
        $this->app->bind(PokemonApiInterface::class, PokemonApiService::class);

        // PokemonApiService
        $this->app->when(PokemonApiService::class)
            ->needs('$baseUrl')
            ->give(config('pokemon.api_url'));

        $this->app->when(PokemonApiService::class)
            ->needs('$maxId')
            ->give(config('pokemon.max_id'));

        $this->app->when(PokemonApiService::class)
            ->needs('$timeout')
            ->give(config('pokemon.api_timeout', 60));

        $this->app->when(PokemonApiService::class)
            ->needs('$retries')
            ->give(config('pokemon.api_retries', 3));

        $this->app->when(PokemonApiService::class)
            ->needs('$retryDelay')
            ->give(config('pokemon.api_retry_delay', 100));
    }
}