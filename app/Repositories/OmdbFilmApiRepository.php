<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OmdbApiRepositoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class OmdbFilmApiRepository implements OmdbApiRepositoryInterface
{
    private const API_KEY = '41b01be2';
    private const BASE_URI = 'http://www.omdbapi.com/';
    private ClientInterface $httpClient;

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Метод отправляет запрос на сервер для получения информации о фильме
     *
     * @param string $omdbId
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function prepareRequest(string $omdbId): ResponseInterface
    {
        $query = [
            'i' => $omdbId,
            'apikey' => self::API_KEY,
        ];

         return $this->httpClient->request('GET', self::BASE_URI, ['query' => $query]);
    }

    /**
     * @throws GuzzleException
     */
    public function findFilmById(string $omdbId): array
    {
        $response = $this->prepareRequest($omdbId);
        return json_decode($response->getBody()->getContents(), true);
    }
}
