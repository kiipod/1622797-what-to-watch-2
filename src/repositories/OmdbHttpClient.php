<?php

namespace src\repositories;

use src\repositories\Interfaces\HttpClientInterface;

class OmdbHttpClient implements HttpClientInterface
{
    private string $apiKey = '41b01be2';
    private string $baseUri = 'http://www.omdbapi.com/?i=%1$s&apikey=%2$s&plot=full&r=json';


    public function prepareRequest($filmId): string
    {
        return sprintf($this->baseUri, $filmId, $this->apiKey);
    }

    public function sendRequest($filmId): bool|string
    {
        return file_get_contents($this->prepareRequest($filmId));
    }
}