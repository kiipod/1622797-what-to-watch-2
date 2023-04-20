<?php

namespace App\Services;

use App\Services\Interfaces\MovieInterface;
use App\Services\HttpClients\OmdbHttpClient;

class GetFilmService implements MovieInterface
{
    protected $client;

    public function __construct(OmdbHttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $filmId
     * @return mixed
     */
    public function getFilm($filmId): mixed
    {
        $response = $this->client->sendRequest($filmId);

        return json_decode($response, true);
    }
}
