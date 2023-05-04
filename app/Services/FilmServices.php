<?php

namespace App\Services;

use App\Models\Film;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FilmServices
{
    public const DEFAULT_LIMIT = 5;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_PAGE_SIZE = 8;
    public const DEFAULT_PAGE = 1;
    public const FILM_DEFAULT_STATUS = 'ready';
    public const FILM_DEFAULT_ORDER_BY = 'released';
    public const FILM_DEFAULT_ORDER_TO = 'DESC';

    /**
     * Метод осуществляет поиск фильма по id
     *
     * @param int $filmId
     * @param array $columns
     * @return Model|null
     */
    public function getFilmById(int $filmId, array $columns = ['*']): ?Model
    {
        return Film::with([
            'genres',
            'actors',
            'directors',
            'comments'
        ])->where('id', '=', $filmId)->firstOrFail($columns);
    }

    /**
     * Метод обновляет рейтинг фильма, высчитывая его как среднее арифметическое значение
     *
     * @param int $filmId
     * @param int $newCommentRating
     * @param int|null $latestRating
     * @return Model
     */
    public function updateRating(
        int $filmId,
        int $newCommentRating,
        int $latestRating = null
    ): Model {
        $film = Film::whereId($filmId)->first();

        if (!$film) {
            throw new NotFoundHttpException("Фильм с ID {$filmId} не найден", null, 404);
        }

        $currentRating = $film->rating;
        $currentScores = $film->scores_count;

        if ($latestRating) {
            $newScoresCount = --$currentScores;
            $currentRating = ($currentRating * $currentScores - $latestRating) / $newScoresCount;
            $currentScores = $newScoresCount;
        }

        $newScoresCount = ++$currentScores;
        $newRating = ($currentRating * $currentScores + $newCommentRating) / $newScoresCount;

        $film->update([
            'rating' => round($newRating, 2),
            'scores_count' => $newScoresCount
        ], ['touch' => false]);

        return $film;
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
            static fn ($genre) => $genre['genre'],
            Film::whereId($filmId)->first()->genres->toArray()
        );

        return Film::query()->whereHas('genres', static function ($query) use ($genres) {
            if ($genres) {
                $query->whereIn('genre', $genres);
            }
        })
            ->select(['id', 'title', 'preview_image', 'preview_video_link'])
            ->whereNot('id', '=', $filmId)
            ->limit(4)
            ->get();
    }

    /**
     * Метод осуществляет фильтрацию списка фильмов по выбранным параметрам
     *
     * @param array $queryParams
     * @return LengthAwarePaginator
     */
    public function getFilteredFilms(array $queryParams): LengthAwarePaginator
    {
        $queryParams['limit'] = $queryParams['limit'] ?? self::DEFAULT_LIMIT;
        $queryParams['offset'] = $queryParams['offset'] ?? self::DEFAULT_OFFSET;
        $queryParams['pageSize'] = $queryParams['pageSize'] ?? self::DEFAULT_PAGE_SIZE;
        $queryParams['page'] = $queryParams['page'] ?? self::DEFAULT_PAGE;
        $queryParams['status'] = $queryParams['status'] ?? self::FILM_DEFAULT_STATUS;
        $queryParams['order_by'] = $queryParams['order_by'] ?? self::FILM_DEFAULT_ORDER_BY;
        $queryParams['order_to'] = $queryParams['order_to'] ?? self::FILM_DEFAULT_ORDER_TO;
        $queryParams['genre'] = $queryParams['genre'] ?? null;

        return Film::query()
            ->whereHas('genres', static function ($query) use ($queryParams) {
                if ($queryParams['genre']) {
                    $query->where('genre', '=', $queryParams['genre']);
                }
            })
            ->select(['id', 'title', 'preview_image', 'status', 'released', 'rating'])
            ->where('status', '=', $queryParams['status'])
            ->orderBy($queryParams['order_by'], $queryParams['order_to'])
            ->limit($queryParams['limit'])
            ->offset($queryParams['offset'])
            ->paginate(
                perPage: $queryParams['pageSize'],
                page: $queryParams['page']
            );
    }
}
