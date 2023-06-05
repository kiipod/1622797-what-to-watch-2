<?php

namespace App\Providers;

use App\Repositories\HtmlAcademyFilmApiRepository;
use App\Repositories\Interfaces\HtmlAcademyApiRepositoryInterface;
use App\Repositories\Interfaces\OmdbApiRepositoryInterface;
use App\Repositories\OmdbFilmApiRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class ExternalApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(HtmlAcademyApiRepositoryInterface::class, HtmlAcademyFilmApiRepository::class);
        $this->app->bind(OmdbApiRepositoryInterface::class, OmdbFilmApiRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
