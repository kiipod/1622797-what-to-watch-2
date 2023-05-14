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
    public function findFilmById(string $omdbId): array
    {
        $response = $this->prepareRequest($omdbId);

        $filmData = json_decode($response->getBody()->getContents(), true);

        return [
            'title' => $filmData['Title'],
            'poster_image' => $filmData['Poster'],
            'preview_image' => null,
            'background_image' => null,
            'background_color' => null,
            'video_link' => null,
            'preview_video_link' => null,
            'description' => $filmData['Plot'],
            'directors' => $filmData['Director'],
            'released' => (int) $filmData['Year'],
            'run_time' => (int) $filmData['Runtime'],
            'rating' => (float) $filmData['imbdRating'],
            'scores_count' => (int) str_replace(',', '', $filmData['imdbVotes']),
            'imdb_id' => $filmData['imdbID'],
            'actors' => array_map('trim', explode(',', $filmData['Actors'])),
            'genres' => array_map('trim', explode(',', $filmData['Genre']))
        ];
    }
}
