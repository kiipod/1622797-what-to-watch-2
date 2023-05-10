<?php

namespace App\Services;

use App\Services\Interfaces\MovieInterface;

class GetFilmService implements MovieInterface
{
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $filmId
     * @return mixed
     */
    public function getFilm($filmId): mixed
    {
        $response = $this->httpClient->findFilmById($filmId);

        return json_decode($response, true);
    }
}
