<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Http\Responses\SuccessResponse;
use App\Models\Genre;
use App\Services\GenreServices;

class GenreController extends Controller
{
    /**
     * @param Genre $genreModel
     * @param GenreServices $genreServices
     */
    public function __construct(private Genre $genreModel, private GenreServices $genreServices)
    {
    }

    /**
     * Получение списка жанров
     *
     * @return SuccessResponse
     */
    public function index(): SuccessResponse
    {
        $genres = $this->genreModel->getAllGenre();

        return new SuccessResponse(data: ['genres' => $genres]);
    }

    /**
     * Обновление названия жанров. Метод доступен только модератору
     *
     * @param GenreRequest $request
     * @param Genre $genre
     * @return SuccessResponse
     */
    public function update(GenreRequest $request, Genre $genre): SuccessResponse
    {
        $validated = $request->validated();
        $genreId = $genre->id;

        $updatedGenre = $this->genreServices->genreUpdate($genreId, $validated['genre']);

        return new SuccessResponse(data: ['updatedGenre' => $updatedGenre]);
    }
}
