<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Film;
use App\Models\Genre;
use App\Services\HttpClients\HtmlAcademyHttpClient;
use App\Services\Interfaces\MovieInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetFilmService implements MovieInterface
{
    /**
     * @param HtmlAcademyHttpClient $httpClient
     */
    public function __construct(private HtmlAcademyHttpClient $httpClient)
    {
    }

    /**
     * Метод осуществляет поиск фильма в API по его идентификатору
     *
     * @param int $ombdId
     * @return array
     * @throws GuzzleException
     */
    public function searchFilm(int $ombdId): array
    {
        return $this->httpClient->findFilmById($ombdId);
    }

    /**
     * Метод осуществляет подготовку Модели для загрузки в БД
     *
     * @param array $filmData
     * @return Film
     */
    private function createFilmModel(array $filmData): Film
    {
        return new Film([
            'title' => $filmData['title'],
            'poster_image' => $filmData['poster_image'],
            'description' => $filmData['description'],
            'director' => $filmData['director'],
            'run_time' => $filmData['run_time'],
            'released' => $filmData['released'],
            'imdb_id' => $filmData['imdb_id'],
            'status' => 'pending',
            'video_link' => $filmData['video_link'],
        ]);
    }

    /**
     * Метод сохраняет/обновляет инфо о фильме в БД
     *
     * @param array $filmData
     * @return void
     * @throws Throwable
     */
    public function saveFilm(array $filmData): void
    {
        try {
            $actorsId = [];
            $genresId = [];
            $actors = $filmData['actors'];
            $genres = $filmData['genres'];
            $director = $filmData['director'];

            DB::beginTransaction();

            foreach ($actors as $actor) {
                $actorsId[] = Actor::firstOrCreate(['name' => $actor])->id;
            }

            foreach ($genres as $genre) {
                $genresId[] = Genre::firstOrCreate(['genre' => $genre])->id;
            }

            $film = $this->createFilmModel($filmData);
            $film->save();

            $film->actors()->attach($actorsId);
            $film->genres()->attach($genresId);
            $film->director()->attach($director);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::warning($exception->getMessage());
        }
    }
}
