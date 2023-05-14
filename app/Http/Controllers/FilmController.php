<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use App\Http\Responses\FailPageNotFound;
use App\Http\Responses\Success;
use App\Jobs\AddFilmJob;
use App\Models\Film;
use App\Services\FilmServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FilmController extends Controller
{
    /**
     * Метод отвечает за показ главной страницы с фильмами
     *
     * @param Request $request
     * @return Success
     */
    public function index(Request $request): Success
    {
        $params = $request->all();
        $authUser = $request->user('sanctum');

        if (
            isset($queryParams['status']) &&
            $queryParams['status'] !== FilmServices::FILM_DEFAULT_STATUS &&
            ($authUser === null ||
                $authUser->is_moderator === false)
        ) {
            $queryParams['status'] = FilmServices::FILM_DEFAULT_STATUS;
        }

        $filmServices = new FilmServices();
        $films = $filmServices->getFilteredFilms($params);

        return new Success(data: $films);
    }

    /**
     * Метод отвечает за добавление фильма в базу
     *
     * @param AddFilmRequest $request
     * @return Success
     */
    public function store(AddFilmRequest $request): Success
    {
        $imdbId = $request->validated()['imdb_id'];

        AddFilmJob::dispatch($imdbId);

        return new Success(['message' => 'Фильм успешно сохранен в базу'], 201);
    }

    /**
     * Метод отвечает за показ страницы с фильмом
     *
     * @param int $filmId
     * @return Success|FailPageNotFound|bool
     */
    public function show(int $filmId): Success|FailPageNotFound|bool
    {
        $filmServices = new FilmServices();
        $film = $filmServices->getFilmById($filmId);
        if (!$film) {
            return new FailPageNotFound();
        }

        $authUser = Auth::user();
        if ($authUser) {
            return (bool)$authUser->favorites()
                ->where('id', '=', $filmId)->first();
        }

        return new Success(data: $film);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFilmRequest $request
     * @param int $filmId
     * @return Success
     * @throws Throwable
     */
    public function update(UpdateFilmRequest $request, int $filmId): Success
    {
        $filmServices = new FilmServices();

        $film = Film::find($filmId);
        $updatedFilm = $filmServices->updateFilmInfo($request->validated(), $film);

        return new Success(data: $updatedFilm);
    }

    /**
     * Метод отвечает за показ похожих фильмов
     *
     * @param int $filmId
     * @return Success
     */
    public function getSimilar(int $filmId): Success
    {
        $filmServices = new FilmServices();
        $similarFilms = $filmServices->getSimilarFilms($filmId);

        return new Success(data: $similarFilms);
    }
}
