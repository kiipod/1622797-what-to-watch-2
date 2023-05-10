<?php

namespace App\Services\HttpClients;

use App\Services\Interfaces\HttpClientInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class OmdbHttpClient implements HttpClientInterface
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
    public function findFilmById(string $omdbId)
    {
        $response = $this->prepareRequest($omdbId);

        $filmData = json_decode($response->getBody()->getContents(), true);

        return [
            'title' => $filmData['name'],
            'poster_image' => $filmData['poster'],
            'preview_image' => $filmData['icon'],
            'background_image' => $filmData['background'],
            'background_color' => null,
            'video_link' => $filmData['video'],
            'preview_video_link' => $filmData['preview'],
            'description' => $filmData['desc'],
            'directors' => $filmData['director'],
            'released' => (int) $filmData['released'],
            'run_time' => (int) $filmData['run_time'],
            'imdb_id' => $filmData['imdb_id'],
            'actors' => $filmData['actors'],
            'genres' => $filmData['genres']
        ];
    }
}
