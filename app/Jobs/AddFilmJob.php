<?php

namespace App\Jobs;

use App\Dto\HtmlAcademyFilmDto;
use App\Dto\OmdbFilmDto;
use App\Repositories\Interfaces\HtmlAcademyApiRepositoryInterface;
use App\Repositories\Interfaces\OmdbApiRepositoryInterface;
use App\Services\FilmServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class AddFilmJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 10;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $omdbId)
    {
    }

    /**
     * Execute the job.
     *
     * @param HtmlAcademyApiRepositoryInterface $htmlAcademyApiRepository
     * @param OmdbApiRepositoryInterface $omdbApiRepository
     * @param FilmServices $filmServices
     * @return void
     * @throws Throwable
     */
    public function handle(
        HtmlAcademyApiRepositoryInterface $htmlAcademyApiRepository,
        OmdbApiRepositoryInterface $omdbApiRepository,
        FilmServices $filmServices
    ): void {
        $omdbResponse = $omdbApiRepository->findFilmById($this->omdbId);

        $filmInfo = (array)$omdbResponse['data'];
        $emptyData = 'N/A';

        $title = $filmInfo['Title'] ?? null;
        $released = $filmInfo['Released'] ?? null;
        $runTime = $filmInfo['Runtime'] ?? null;
        $genres = $filmInfo['Genre'] ?? null;
        $director = $filmInfo['Director'] ?? null;
        $actors = $filmInfo['Actors'] ?? null;
        $description = $filmInfo['Plot'] ?? null;
        $posterImage = $filmInfo['Poster'] ?? null;
        $rating = $filmInfo['imdbRating'] ?? null;
        $scoresCount = $filmInfo['imdbVotes'] ?? null;

        $omdbFilmApiDto = new OmdbFilmDto(
            title: $title === $emptyData ? null : $title,
            released: $released === $emptyData ? null : $released,
            runTime: $runTime === $emptyData ? null : $runTime,
            genres: $genres === $emptyData ? null : $genres,
            director: $director === $emptyData ? null : $director,
            actors: $actors === $emptyData ? null : $actors,
            description: $description === $emptyData ? null : $description,
            posterImage: $posterImage === $emptyData ? null : $posterImage,
            rating: $rating === $emptyData ? null : $rating,
            scoresCount: $scoresCount === $emptyData ? null : $scoresCount
        );

        $filmServices->saveFilmInfo($this->omdbId, $omdbFilmApiDto);

        $htmlAcademyResponse = $htmlAcademyApiRepository->findFilmById($this->omdbId);

        $additionalFilmInfo = (array)$htmlAcademyResponse['data'];

        $htmlAcademyFilmDto = new HtmlAcademyFilmDto(
            title: $additionalFilmInfo['name'] ?? null,
            previewImage: $additionalFilmInfo['icon'] ?? null,
            backgroundImage: $additionalFilmInfo['background'] ?? null,
            videoLink: $additionalFilmInfo['video'] ?? null,
            previewVideoLink: $additionalFilmInfo['preview'] ?? null
        );

        $filmServices->saveAdditionalFilmInfo($this->omdbId, $htmlAcademyFilmDto);
    }
}
