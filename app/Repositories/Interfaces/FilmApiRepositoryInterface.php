<?php

namespace App\Repositories\Interfaces;

interface FilmApiRepositoryInterface
{
    public function prepareRequest(string $omdbId);

    public function findFilmById(string $omdbId);
}
