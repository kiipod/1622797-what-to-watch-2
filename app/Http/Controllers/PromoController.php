<?php

namespace App\Http\Controllers;

use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FilmServices;
use Illuminate\Support\Facades\DB;
use Throwable;

class PromoController extends Controller
{
    /**
     * @param FilmServices $filmServices
     */
    public function __construct(private FilmServices $filmServices)
    {
    }

    /**
     * Метод отвечает за получение Промо-фильма
     *
     * @return NotFoundResponse|SuccessResponse
     */
    public function index(): NotFoundResponse|SuccessResponse
    {
        $promoFilm = $this->filmServices->getPromoFilm();

        if (!$promoFilm) {
            return new NotFoundResponse();
        }
        return new SuccessResponse(data: $promoFilm);
    }

    /**
     * Метод отвечает за установку/снятие Промо-фильма
     *
     * @param int $filmId
     * @return SuccessResponse
     * @throws Throwable
     */
    public function store(int $filmId): SuccessResponse
    {
        $currentFilm = $this->filmServices->getFilmById($filmId);

        DB::beginTransaction();
        try {
            if ($previousPromoFilm = $this->filmServices->getPromoFilm()) {
                $previousPromoFilm->update(['promo' => false]);
            }

            $currentFilm->update(['promo' => true]);

            DB::commit();

            return new SuccessResponse(data: $currentFilm);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
