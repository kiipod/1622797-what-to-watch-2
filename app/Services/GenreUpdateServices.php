<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;

class GenreUpdateServices
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

        if ($genre !== $updatedGenre->title) {
            $updatedGenre->title = $genre;
        }

        $updatedGenre->update();

        return $updatedGenre;
    }
}
