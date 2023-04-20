<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Http\Responses\Success;
use App\Models\Genre;
use App\Services\GenreUpdateServices;

class GenreController extends Controller
{
    /**
     * Получение списка жанров
     *
     * @return Success
     */
    public function index(): Success
    {
        $genreClass = new Genre();
        $genres = $genreClass->getAllGenre();

        return new Success(data: ['genres' => $genres]);
    }

    /**
     * Обновление названия жанров. Метод доступен только модератору
     *
     * @param GenreRequest $request
     * @param Genre $genre
     * @return Success
     */
    public function update(GenreRequest $request, Genre $genre): Success
    {
        $updatedGenreService = new GenreUpdateServices();

        $validated = $request->validated();
        $genreId = $genre->id;

        $updatedGenre = $updatedGenreService->genreUpdate($genreId, $validated['title']);

        return new Success(data: ['updatedGenre' => $updatedGenre]);
    }
}
