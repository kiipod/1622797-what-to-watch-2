<?php

namespace App\Http\Controllers;

use App\Http\Responses\FailAuthResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FavoriteServices;
use App\Services\FilmServices;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @param FilmServices $filmServices
     * @param FavoriteServices $favoriteServices
     */
    public function __construct(
        private FilmServices $filmServices,
        private FavoriteServices $favoriteServices
    ) {
    }

    /**
     * Метод показывает все избранные фильмы пользователя
     *
     * @return SuccessResponse|FailAuthResponse
     */
    public function index(): SuccessResponse|FailAuthResponse
    {
        $user = Auth::user();

        if (!$user) {
            return new FailAuthResponse();
        }

        $userId = $user->id;
        $favoriteFilms = $this->favoriteServices->getFavoriteFilms($userId);
        return new SuccessResponse(data: $favoriteFilms);
    }

    /**
     * Метод отвечает за добавление фильма в список избранных
     *
     * @param int $filmId
     * @return SuccessResponse|FailAuthResponse
     */
    public function store(int $filmId): SuccessResponse|FailAuthResponse
    {
        $film = $this->filmServices->getFilmById($filmId);
        $user = Auth::user();

        if ($user->favorites()->where('film_id', '=', $filmId)->first() !== null) {
            return new FailAuthResponse();
        }

        $user->favorites()->attach($filmId);

        return new SuccessResponse(data: $film);
    }

    /**
     * Метод отвечает за удаление фильма из списка избранных
     *
     * @param int $filmId
     * @return SuccessResponse|FailAuthResponse
     */
    public function destroy(int $filmId): SuccessResponse|FailAuthResponse
    {
        $film = $this->filmServices->getFilmById($filmId);
        $user = Auth::user();

        if ($user->favorites()->where('film_id', '=', $film)->first() === null) {
            return new FailAuthResponse();
        }

        $user->favorites()->detach($film);

        return new SuccessResponse(data: ['Фильм удален из списка Избранных']);
    }
}
