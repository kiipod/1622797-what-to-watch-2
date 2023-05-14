<?php

namespace App\Services\Interfaces;

interface MovieInterface
{
    public function searchFilm(int $ombdId);

    public function saveFilm(array $filmData);
}
