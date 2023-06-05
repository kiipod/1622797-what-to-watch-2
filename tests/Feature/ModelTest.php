<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест модели Актеры
     *
     * @return void
     */
    public function test_model_actor(): void
    {
        $actor = Actor::factory()->create();
        $this->assertInstanceOf(Actor::class, $actor);
    }

    /**
     * Тест модели Фильмы
     *
     * @return void
     */
    public function test_model_film(): void
    {
        $film = Film::factory()->create();
        $this->assertInstanceOf(Film::class, $film);
    }

    /**
     * Тест модели Жанры
     *
     * @return void
     */
    public function test_model_genre(): void
    {
        $genre = Genre::factory()->create();
        $this->assertInstanceOf(Genre::class, $genre);
    }

    /**
     * Тест модели Пользователи
     *
     * @return void
     */
    public function test_model_user(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Тест модели Комментарии
     *
     * @return void
     */
    public function test_model_comment(): void
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $comment = Comment::factory()->create(['film_id' => $film->id, 'user_id' => $user->id]);

        $this->assertInstanceOf(Comment::class, $comment);

        $this->assertNotEmpty($comment->user_id);
        $this->assertInstanceOf(User::class, $comment->user);

        $this->assertNotEmpty($comment->film_id);
        $this->assertInstanceOf(Film::class, $comment->film);

        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($film->id, $comment->film_id);
    }

    /**
     * Тест модели Избранные фильмы
     *
     * @return void
     */
    public function test_model_favorite(): void
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $favorite = Favorite::factory()->create(['film_id' => $film->id, 'user_id' => $user->id]);

        $this->assertInstanceOf(Favorite::class, $favorite);

        $this->assertNotEmpty($favorite->user_id);
        $this->assertInstanceOf(User::class, $favorite->user);

        $this->assertEquals($user->id, $favorite->user_id);
        $this->assertEquals($film->id, $favorite->film_id);
    }
}
