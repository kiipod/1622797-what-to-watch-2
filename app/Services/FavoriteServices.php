<?php

namespace App\Services;

use App\Models\Film;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FavoriteServices
{
    /**
     * Метод отвечает за получение избранных фильмов пользователя
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getFavoriteFilms(int $userId): LengthAwarePaginator
    {
        $filmsId = array_map(
            static fn ($film) => $film['id'],
            User::whereId($userId)->first()->favorites->toArray()
        );

        return Film::query()->where('status', '=', 'ready')
            ->select(['id', 'title', 'preview_image', 'preview_video_link'])
            ->whereIn('id', $filmsId)
            ->orderBy('released', 'DESC')
            ->limit(8)
            ->offset(8)
            ->paginate(perPage: 8, page: 1);
    }
}
