<?php

namespace src\repositories;

use src\repositories\Interfaces\MovieInterface;

class MovieRepository implements MovieInterface
{
    private $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $filmId
     * @return array|null
     */
    public function getMoviesInfo($filmId): ?array
    {
        $response = $this->httpClient->sendRequest($filmId);

        return json_decode($response, true);
    }
}
