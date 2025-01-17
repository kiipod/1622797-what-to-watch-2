<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;

class GenreServices
{
    /**
     * Метод отвечает за обновление названия жанра
     *
     * @param int $id
     * @param string $genre
     * @return Model
     */
    public function genreUpdate(int $id, string $genre): Model
    {
        $updatedGenre = Genre::find($id);

        if ($genre !== $updatedGenre->genre) {
            $updatedGenre->genre = $genre;
        }

        $updatedGenre->update();

        return $updatedGenre;
    }
}
