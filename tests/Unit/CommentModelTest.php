<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест проверяет наличие имени автора комментария
     *
     * @return void
     */
    public function test_get_comment_author_name()
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $comment = Comment::factory()->state(
            new Sequence(fn ($sequence) => ['film_id' => $film, 'user_id' => $user])
        )->create();

        $author = $comment->user->name;

        $this->assertEquals($user->name, $author);
    }

    /**
     * Тест для проверки оставленных анонимных комментариев и дефолтного имени
     *
     * @return void
     */
    public function test_get_anonymous_author_name()
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $comment = Comment::factory()->state(
            new Sequence(fn ($sequence) => ['film_id' => $film, 'user_id' => $user])
        )->make();

        $user->delete();

        $author = $comment->user->name;

        $this->assertEquals('Гость', $author);
    }
}
