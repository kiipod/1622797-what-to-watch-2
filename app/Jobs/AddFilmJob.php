<?php

namespace App\Jobs;

use App\Services\GetFilmService;
use GuzzleHttp\Exception\GuzzleException;
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
     * @param GetFilmService $filmService
     * @return void
     * @throws GuzzleException
     * @throws Throwable
     */
    public function handle(GetFilmService $filmService): void
    {
        $filmData = $filmService->searchFilm($this->omdbId);
        $filmService->saveFilm($filmData);
    }
}
