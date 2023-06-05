<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SuccessResponse;
use App\Jobs\AddFilmJob;
use App\Models\Film;
use App\Services\FilmServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FilmController extends Controller
{
    /**
     * @param FilmServices $filmServices
     */
    public function __construct(private FilmServices $filmServices)
    {
    }

    /**
     * Метод отвечает за показ главной страницы с фильмами
     *
     * @param Request $request
     * @return SuccessResponse
     */
    public function index(Request $request): SuccessResponse
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

        $films = $this->filmServices->getFilteredFilms($params);

        return new SuccessResponse(data: $films);
    }

    /**
     * Метод отвечает за добавление фильма в базу
     *
     * @param AddFilmRequest $request
     * @return SuccessResponse
     */
    public function store(AddFilmRequest $request): SuccessResponse
    {
        $imdbId = $request->validated()['imdb_id'];

        AddFilmJob::dispatch($imdbId);

        return new SuccessResponse(['message' => 'Фильм успешно сохранен в базу'], 201);
    }

    /**
     * Метод отвечает за показ страницы с фильмом
     *
     * @param int $filmId
     * @return SuccessResponse|NotFoundResponse|bool
     */
    public function show(int $filmId): SuccessResponse|NotFoundResponse|bool
    {
        $film = $this->filmServices->getFilmById($filmId);
        if (!$film) {
            return new NotFoundResponse();
        }

        $authUser = Auth::user();
        if ($authUser) {
            return (bool)$authUser->favorites()
                ->where('id', '=', $filmId)->first();
        }

        return new SuccessResponse(data: $film);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFilmRequest $request
     * @param int $filmId
     * @return SuccessResponse
     * @throws Throwable
     */
    public function update(UpdateFilmRequest $request, int $filmId): SuccessResponse
    {
        $film = Film::find($filmId);
        $updatedFilm = $this->filmServices->updateFilmInfo($request->validated(), $film);

        return new SuccessResponse(data: $updatedFilm);
    }

    /**
     * Метод отвечает за показ похожих фильмов
     *
     * @param int $filmId
     * @return SuccessResponse
     */
    public function getSimilar(int $filmId): SuccessResponse
    {
        $similarFilms = $this->filmServices->getSimilarFilms($filmId);

        return new SuccessResponse(data: $similarFilms);
    }
}
