<?php

namespace App\Http\Controllers;

use App\Http\Responses\FailPageNotFound;
use App\Http\Responses\Success;
use App\Services\FilmServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Success
     */
    public function index()
    {
        return new Success();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Success
     */
    public function store(Request $request)
    {
        return new Success();
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
        $film = $filmServices->findById($filmId);
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
     * @param Request $request
     * @param  int  $id
     * @return Success
     */
    public function update(Request $request, $id)
    {
        return new Success();
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
