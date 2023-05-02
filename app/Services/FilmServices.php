<?php

namespace App\Services;

use App\Models\Film;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FilmServices
{
    /**
     * Метод осуществляет поиск фильма по id
     *
     * @param int $filmId
     * @param array $columns
     * @return Model|null
     */
    public function findById(int $filmId, array $columns = ['*']): ?Model
    {
        return Film::with([
            'genres',
            'actors',
            'directors',
            'comments'
        ])->where('id', '=', $filmId)->firstOrFail($columns);
    }

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

    /**
     * Метод отвечает за получение списка похожих фильмов
     *
     * @param int $filmId
     * @return Collection|null
     */
    public function getSimilarFilms(int $filmId): ?Collection
    {
        $genres = array_map(
            static fn ($genre) => $genre['title'],
            Film::whereId($filmId)->first()->genres->toArray()
        );

        return Film::query()->whereHas('genres', static function ($query) use ($genres) {
            if ($genres) {
                $query->whereIn('title', $genres);
            }
        })
            ->select(['id', 'title', 'preview_image', 'preview_video_link'])
            ->whereNot('id', '=', $filmId)
            ->limit(4)
            ->get();
    }
}
