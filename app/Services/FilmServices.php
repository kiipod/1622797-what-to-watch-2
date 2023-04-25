<?php

namespace App\Services;

use App\Models\Film;

class FilmServices
{
    /**
     * Метод высчитывает рейтинг фильма, как среднее арифметическое значение
     *
     * @param int $filmId
     * @return float
     */
    public function getFilmRating(int $filmId): float
    {
        $film = Film::whereId($filmId)->first();
        $comments = $film->comments();

        return round($comments->avg('rating'), 1);
    }

    /**
     * Метод отвечает за обновление рейтинга фильма, после редактирования комментария, если рейтинг был изменен
     *
     * @param int $filmId
     * @param int $newCommentRating
     * @return float
     */
    public function updateRating(int $filmId, int $newCommentRating): float
    {
        if ($newCommentRating) {
            $this->getFilmRating($filmId);
        }

        return $this->getFilmRating($filmId);
    }
}
