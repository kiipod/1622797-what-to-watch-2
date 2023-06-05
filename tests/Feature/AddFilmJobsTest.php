<?php

namespace Tests\Feature;

use App\Jobs\AddFilmJob;
use App\Models\Film;
use App\Repositories\Interfaces\HtmlAcademyApiRepositoryInterface;
use App\Repositories\Interfaces\OmdbApiRepositoryInterface;
use App\Services\FilmServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Throwable;

class AddFilmJobsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Throwable
     */
    public function test_adding_film_in_database()
    {
        $imdbId = 'tt0111161';

        Film::factory()->create([
            'status' => 'pending',
            'imdb_id' => $imdbId
        ]);

        $omdbRepository = $this->createMock(OmdbApiRepositoryInterface::class);
        $omdbRepository->expects($this->exactly(2))
            ->method('findFilmById')
            ->with($imdbId)
            ->willReturn(
                json_decode(
                    file_get_contents(
                        base_path('tests/Fixtures/omdb-fake-response.json')
                    ),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                )
            );

        $htmlAcademyRepository = $this->createMock(HtmlAcademyApiRepositoryInterface::class);
        $htmlAcademyRepository->expects($this->exactly(2))
            ->method('findFilmById')
            ->with($imdbId)
            ->willReturn(
                json_decode(
                    file_get_contents(
                        base_path('tests/Fixtures/html-academy-fake-response.json')
                    ),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                )
            );

        $filmServices = new FilmServices();

        (new AddFilmJob($imdbId))->handle($htmlAcademyRepository, $omdbRepository, $filmServices);

        $omdbResponse = $omdbRepository->findFilmById($imdbId);
        $htmlAcademyResponse = $htmlAcademyRepository->findFilmById($imdbId);

        $this->assertIsArray($omdbResponse);
        $this->assertIsArray($htmlAcademyResponse);
    }

    /**
     * Тест на проверку очереди
     *
     * @return void
     */
    public function test_add_task_queue(): void
    {
        Queue::fake();

        $imdbId = 'tt0111161';

        AddFilmJob::dispatch($imdbId);

        Queue::assertPushed(AddFilmJob::class);
    }
}
