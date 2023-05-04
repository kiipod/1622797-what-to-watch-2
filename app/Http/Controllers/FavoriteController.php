<?php

namespace App\Http\Controllers;

use App\Http\Responses\FailAuth;
use App\Http\Responses\Success;
use App\Services\FavoriteServices;
use App\Services\FilmServices;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Метод показывает все избранные фильмы пользователя
     *
     * @return Success|FailAuth
     */
    public function index(): Success|FailAuth
    {
        $favoriteServices = new FavoriteServices();
        $user = Auth::user();

        if (!$user) {
            return new FailAuth();
        }

        $userId = $user->id;
        $favoriteFilms = $favoriteServices->getFavoriteFilms($userId);
        return new Success(data: $favoriteFilms);
    }

    /**
     * Метод отвечает за добавление фильма в список избранных
     *
     * @param int $filmId
     * @return Success|FailAuth
     */
    public function store(int $filmId): Success|FailAuth
    {
        $filmServices = new FilmServices();

        $film = $filmServices->findById($filmId);
        $user = Auth::user();

        if ($user->favorites()->where('film_id', '=', $filmId)->first() !== null) {
            return new FailAuth();
        }

        $user->favorites()->attach($filmId);

        return new Success(data: $film);
    }

    /**
     * Метод отвечает за удаление фильма из списка избранных
     *
     * @param int $filmId
     * @return Success|FailAuth
     */
    public function destroy(int $filmId): Success|FailAuth
    {
        $filmServices = new FilmServices();

        $film = $filmServices->findById($filmId);
        $user = Auth::user();

        if ($user->favorites()->where('film_id', '=', $filmId)->first() === null) {
            return new FailAuth();
        }

        $user->favorites()->detach($filmId);

        return new Success(data: ['Фильм удален из списка Избранных']);
    }
}
