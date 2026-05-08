<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PokemonApiInterface;
use App\Contracts\PokemonRepositoryInterface;
use App\Contracts\StatsServiceInterface;
use App\Contracts\VisitRepositoryInterface;
use App\Services\PokemonApiService;
use App\Services\PokemonRepository;
use App\Services\StatsService;
use App\Services\VisitRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрация всех интерфейсов
        $this->app->bind(PokemonRepositoryInterface::class, PokemonRepository::class);
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);
        $this->app->bind(StatsServiceInterface::class, StatsService::class);



        // Настройка PokemonApiService с параметрами из конфига
        $this->app->bind(PokemonApiInterface::class, function ($app) {

            return new PokemonApiService(

                config('pokemon.api_url'),
                config('pokemon.max_id')

            );
        });
    }

    public function boot(): void
    {
        //
    }
}
