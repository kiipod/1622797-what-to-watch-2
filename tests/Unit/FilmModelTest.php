<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilmModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест проверяет что рейтинг фильма сходится с алгоритмом расчета рейтинга
     *
     * @return void
     */
    public function test_film_rating()
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $testCountComment = 10;
        $testRating = 7;

        $testAverageRating = ($testRating * $testCountComment) / $testCountComment;

        Comment::factory()->count($testCountComment)->state(new Sequence(
            fn ($sequence) => ['user_id' => $user, 'film_id' => $film, 'rating' => $testRating]
        ))->create();

        $comments = $film->comments;

        $averageRating = round($comments->avg('rating'), 1);

        $this->assertEquals($testAverageRating, $averageRating);
    }
}
