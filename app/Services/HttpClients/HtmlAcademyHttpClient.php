<?php

namespace App\Services\HttpClients;

use App\Services\Interfaces\HttpClientInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HtmlAcademyHttpClient implements HttpClientInterface
{
    private const HTML_ACADEMY_URI = 'http://guide.phpdemo.ru/api/films/';
    private ClientInterface $httpClient;

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $omdbId
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function prepareRequest(string $omdbId): ResponseInterface
    {
        return $this->httpClient->request('GET', self::HTML_ACADEMY_URI . $omdbId);
    }

    /**
     * @param string $omdbId
     * @return array
     * @throws GuzzleException
     */
    public function findFilmById(string $omdbId): array
    {
        $response = $this->prepareRequest($omdbId);

        return json_decode($response->getBody()->getContents(), true);
    }
}
