<?php

namespace src\Services;

use src\repositories\Interfaces\MovieInterface;
use src\repositories\OmdbHttpClient;

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
