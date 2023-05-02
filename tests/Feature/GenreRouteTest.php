<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест на проверку роута жанров
     *
     * @return void
     */
    public function test_genre_get_route()
    {
        Genre::factory()->count(10)->create();

        $this->getJson(route('genres.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'genres' => [['id', 'title']]
                    ]
            ]);
    }

    /**
     * Тест на проверку, что модератор может редактировать жанры
     *
     * @return void
     */
    public function test_can_update_genre_by_moderator()
    {
        $genre = Genre::factory()->create();
        $user = User::factory()->moderator()->create();

        $newGenre = 'Action';
        $genreId = $genre->id;

        $this->actingAs($user)
            ->patchJson('/api/genres/' . $genreId, ['title' => $newGenre])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'updatedGenre' => ['id', 'title']
                ]
            ]);
    }

    /**
     * Тест на проверку, что неавторизованный пользователь не может редактировать жанры
     *
     * @return void
     */
    public function test_not_update_genre_by_no_auth()
    {
        $genre = Genre::factory()->create();

        $newGenre = 'Action';
        $genreId = $genre->id;

        $this->patchJson('/api/genres/' . $genreId, ['title' => $newGenre])
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест на проверку, что авторизованный пользователь, не модератор, не может редактировать жанры
     *
     * @return void
     */
    public function test_not_update_genre_by_user()
    {
        $genre = Genre::factory()->create();
        $user = User::factory()->create();

        $newGenre = 'Action';
        $genreId = $genre->id;

        $this->actingAs($user)
            ->patchJson('/api/genres/' . $genreId, ['title' => $newGenre])
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }
}
