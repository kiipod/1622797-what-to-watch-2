<?php

namespace App\Services\Interfaces;

interface HttpClientInterface
{
    public function prepareRequest(string $omdbId);

    public function findFilmById(string $omdbId);
}
