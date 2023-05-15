<?php

namespace App\Http\Controllers;

use App\Http\Responses\FailPageNotFound;
use App\Http\Responses\Success;
use App\Services\FilmServices;
use Illuminate\Support\Facades\DB;
use Throwable;

class PromoController extends Controller
{
    /**
     * Метод отвечает за получение Промо-фильма
     *
     * @return FailPageNotFound|Success
     */
    public function index(): FailPageNotFound|Success
    {
        $filmServices = new FilmServices();

        $promoFilm = $filmServices->getPromoFilm();

        if (!$promoFilm) {
            return new FailPageNotFound();
        }
        return new Success(data: $promoFilm);
    }

    /**
     * Метод отвечает за установку/снятие Промо-фильма
     *
     * @param int $filmId
     * @return Success
     * @throws Throwable
     */
    public function store(int $filmId): Success
    {
        $filmServices = new FilmServices();

        $currentFilm = $filmServices->getFilmById($filmId);

        DB::beginTransaction();
        try {
            if ($previousPromoFilm = $filmServices->getPromoFilm()) {
                $previousPromoFilm->update(['promo' => false]);
            }

            $currentFilm->update(['promo' => true]);

            DB::commit();

            return new Success(data: $currentFilm);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
