<?php

namespace Tests\Feature;

use App\Jobs\AddFilmJob;
use App\Models\Film;
use App\Models\FilmGenre;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FilmRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест проверяющий роут get для получения списка фильмов
     *
     * @return void
     */
    public function test_film_get_route()
    {
        Film::factory()->count(35)->create();

        $this->getJson(route('films.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'data',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     * Тест проверяет что модератор имеет права для добавления нового фильма в БД
     *
     * @return void
     */
    public function test_can_add_new_film_by_moderator()
    {
        Queue::fake();

        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->postJson(route('films.store'), ['imdb_id' => 'tt1385384'])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => []
            ]);

        Queue::assertPushed(AddFilmJob::class);
    }

    /**
     * Тест проверяет что обычный пользователь не обладает правами для добавления нового фильма в БД
     *
     * @return void
     */
    public function test_can_not_add_new_film_by_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('films.store'), ['imdb_id' => 'tt1385384'])
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест валидирует данные на ошибку для добавления нового фильма в БД
     *
     * @return void
     */
    public function test_can_not_add_new_film_with_validation_error_by_moderator()
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->postJson(route('films.store'), ['imdb_id' => 't1385384'])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);
    }

    /**
     * Тест проверяет что модератор имеет права редактировать фильм
     *
     * @return void
     */
    public function test_can_update_film_by_moderator()
    {
        $moderator = User::factory()->moderator()->create();
        $film = Film::factory()->create();

        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
            'title' => 'New Film Legendary',
            'imdb_id' => 'tt1234567',
            'status' => 'ready'
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' =>  []
            ]);
    }

    /**
     * Тест проверяет что обычный пользователь не может редактировать фильм
     *
     * @return void
     */
    public function test_can_not_update_film_by_user()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();

        $this->actingAs($user)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'New Film Legendary',
                'imdb_id' => 'tt1234567',
                'status' => 'ready'
            ])
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест проверяет что неаутентифицированный пользователь не может редактировать фильм
     *
     * @return void
     */
    public function test_can_not_update_film_by_no_auth()
    {
        $film = Film::factory()->create();

        $this->patchJson(route('films.update', $film->id), [
                'title' => 'New Film Legendary',
                'imdb_id' => 'tt1234567',
                'status' => 'ready'
            ])
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест проверяет на ошибки валидации полей при редактировании фильма
     *
     * @return void
     */
    public function test_can_not_update_film_with_validation_error_by_moderator()
    {
        $moderator = User::factory()->moderator()->create();
        $film = Film::factory()->create();

        // Ошибка в imdb_id
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'New Film Legendary',
                'imdb_id' => 't1234567',
                'status' => 'ready'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в название фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 12,
                'imdb_id' => 'tt1234567',
                'status' => 'ready'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в содержимом адреса постера
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'poster_image' => 'image'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в пути до файла превью изображения
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'preview_image' => 'path/file-image.png'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в пути до фоновой картинки фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'background_image' => 'img/file-image'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в выборе цвета фона
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'background_color' => '#uio987e'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в адресе ссылки на видео
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'video_link' => '//site.com/video23232'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в адресе ссылки на трейлер фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'preview_video_link' => '//site.com/video23232'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в описание фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'description' => 23232
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в имени режиссера
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'director' => 45
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в имени актера
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'actors' => 'Bill Mudey'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в жанрах
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'genres' => 234546
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в длительности фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'run_time' => 'text text'
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);

        // Ошибка в дате выхода фильма
        $this->actingAs($moderator)
            ->patchJson(route('films.update', $film->id), [
                'title' => 'The New Legendary Film',
                'imdb_id' => 'tt1234567',
                'status' => 'ready',
                'released' => 1654
            ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => []
            ]);
    }

    /**
     * Тест проверяет что информация о фильме доступна всем пользователям
     *
     * @return void
     */
    public function test_show_info_for_film()
    {
        $film = Film::factory()->create();

        $this->getJson(route('films.show', $film->id))
            ->assertOk()
            ->assertJsonStructure([
            'data' =>  [
                'id',
                'title',
                'poster_image',
                'preview_image',
                'background_image',
                'background_color',
                'video_link',
                'preview_video_link',
                'description',
                'rating',
                'scores_count',
                'director',
                'actors',
                'run_time',
                'genres',
                'released',
                'comments'
            ]
        ]);
    }

    /**
     * Тест проверяет что показываются похожие фильмы на выбранный
     *
     * @return void
     */
    public function test_get_similar_films(): void
    {
        Film::factory(10)->create();
        $genre = Genre::factory(['genre' => 'Action'])->create();

        FilmGenre::factory()
            ->count(20)
            ->state(new Sequence(
                fn ($sequence) => [
                    'film_id' => Film::all()->random(),
                    'genre_id' => Genre::all()->random()],
            ))
            ->create();

        $film = Film::factory()->create();
        FilmGenre::factory(['film_id' => $film->id, 'genre_id' => $genre->id])->create();

        $this->getJson(route('films.similar', $film->id))
            ->assertOk()
            ->assertJsonStructure([
            'data' => [[
                'id',
                'title',
                'preview_image',
                'preview_video_link'
            ]]
        ]);
    }
}
