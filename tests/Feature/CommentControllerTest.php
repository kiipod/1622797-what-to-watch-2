<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Метод проверяет работу роута get для комментариев для всех пользователей
     *
     * @return void
     */
    public function test_comment_get_route_by_no_auth()
    {
        $film = Film::factory()
            ->has(Comment::factory(5)
                ->for(User::factory()
                    ->create()))
            ->create();

        $comment = $film->comments->first();

        $this->getJson(route('comments.index', $film->id))
            ->assertOk()
            ->assertJsonFragment([
                'name' => $comment->user->name,
                'text' => $comment->text,
                'rating' => $comment->rating,
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Метод проверяет работу роута get для комментариев для всех зарегистрированных пользователей
     *
     * @return void
     */
    public function test_comment_get_route_by_user()
    {
        $film = Film::factory()
            ->has(Comment::factory(5)
                ->for(User::factory()
                    ->create()))
            ->create();

        $comment = $film->comments->first();

        Sanctum::actingAs(User::factory()->create());
        $this->getJson(route('comments.index', $film->id))
            ->assertOk()
            ->assertJsonFragment([
                'name' => $comment->user->name,
                'text' => $comment->text,
                'rating' => $comment->rating,
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Метод проверяет работу роута get для комментариев для модератора
     *
     * @return void
     */
    public function test_comment_get_route_by_moderator()
    {
        $film = Film::factory()
            ->has(Comment::factory(5)
                ->for(User::factory()
                    ->create()))
            ->create();

        $comment = $film->comments->first();

        Sanctum::actingAs(User::factory()->moderator()->create());
        $this->getJson(route('comments.index', $film->id))
            ->assertOk()
            ->assertJsonFragment([
                'name' => $comment->user->name,
                'text' => $comment->text,
                'rating' => $comment->rating,
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Тест проверяет что неаутентифицированный пользователь не может добавить новый комментарий
     *
     * @return void
     */
    public function test_can_add_new_comment_no_auth()
    {
        $film = Film::factory()->create();
        $testComment = 'Тестовый коммент, тестовый коммент';
        $testRating = 7;

        $this->postJson(
            route('comments.store', $film->id),
            ['text' => $testComment, 'rating' => $testRating]
        )
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест проверяет может ли аутентифицированный пользователь добавить комментарий
     *
     * @return void
     */
    public function test_can_add_new_comment_by_user()
    {
        $film = Film::factory()->create();
        $testComment = 'Тестовый комментарий, тестовый комментарий, тестовый комментарий';
        $testRating = 7;

        Sanctum::actingAs(User::factory()->create());
        $this->postJson(
            route('comments.store', $film->id),
            ['text' => $testComment, 'rating' => $testRating]
        )
            ->assertOk()
            ->assertJsonFragment([
                'text' => $testComment,
                'rating' => $testRating,
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Метод проверяет правила валидации при добавлении нового комментария
     *
     * @return void
     */
    public function test_can_add_new_comment_by_user_no_text()
    {
        $film = Film::factory()->create();
        $testComment = '';
        $testRating = 7;

        Sanctum::actingAs(User::factory()->create());
        $this->postJson(
            route('comments.store', $film->id),
            ['text' => $testComment, 'rating' => $testRating]
        )
            ->assertStatus(422)
            ->assertJsonStructure(['message']);
    }

    /**
     * Метод проверяет что неаутентифицированный пользователь не может отредактировать комментарий
     *
     * @return void
     */
    public function test_can_update_comment_no_auth()
    {
        $film = Film::factory()
            ->has(Comment::factory()
                ->for(User::factory()
                    ->create()))
            ->create();

        $comment = $film->comments->first();

        $newComment = 'Тестовый комментарий, тестовый комментарий, тестовый комментарий';
        $newRating = 8;

        $this->patchJson(
            route('comments.update', $comment->id),
            ['text' => $newComment, 'rating' => $newRating]
        )
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * Метод проверяет что обычный пользователь не может редактировать комментарий, если не является автором
     *
     * @return void
     */
    public function test_can_no_update_comment_by_user()
    {
        $userNotAuthor = User::factory()->create();
        Sanctum::actingAs($userNotAuthor);

        $userAuthor = User::factory()->create();
        $film = Film::factory()->create();

        $newComment = 'Тестовый комментарий, тестовый комментарий, тестовый комментарий';
        $newRating = 8;

        $comment = Comment::factory([
                'user_id' => $userAuthor->id,
                'film_id' => $film->id
            ])->create();

        $this->patchJson(
            route('comments.update', $comment->id),
            ['text' => $newComment, 'rating' => $newRating]
        )
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }

    /**
     * Метод проверяет что автор комментария может редактировать свой комментарий
     *
     * @return void
     */
    public function test_can_update_comment_by_author()
    {
        $userAuthor = User::factory()->create();
        Sanctum::actingAs($userAuthor);

        $newComment = 'Тестовый комментарий, тестовый комментарий, тестовый комментарий';
        $newRating = 8;

        $film = Film::factory()->create();

        $comment = Comment::factory([
                'user_id' => $userAuthor->id,
                'film_id' => $film->id
            ])->create();

        $this->patchJson(
            route('comments.update', $comment->id),
            ['text' => $newComment, 'rating' => $newRating]
        )
            ->assertOk()
            ->assertJsonFragment(['text' => $newComment, 'rating' => $newRating])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Тест проверяет что модератор может редактировать комментарий других пользователей
     *
     * @return void
     */
    public function test_can_update_comment_by_moderator()
    {
        $userModerator = User::factory()->moderator()->create();
        Sanctum::actingAs($userModerator);

        $newComment = 'Тестовый комментарий, тестовый комментарий, тестовый комментарий';
        $newRating = 8;

        $userAuthor = User::factory()->create();
        $film = Film::factory()->create();

        $comment = Comment::factory([
            'user_id' => $userAuthor->id,
            'film_id' => $film->id
        ])->create();

        $this->patchJson(
            route('comments.update', $comment->id),
            ['text' => $newComment, 'rating' => $newRating]
        )
            ->assertOk()
            ->assertJsonFragment(['text' => $newComment, 'rating' => $newRating])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Тест проверяет что неаутентифицированный пользователь не может удалить комментарий
     *
     * @return void
     */
    public function test_can_delete_comment_by_no_auth()
    {
        $film = Film::factory()
            ->has(Comment::factory(5)
                ->for(User::factory()
                    ->create()))
            ->create();

        $comment = $film->comments->first();

        $this->deleteJson(route('comments.destroy', $comment->id))
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест проверяет что обычный пользователь не может удалить чужой комментарий
     *
     * @return void
     */
    public function test_can_delete_comment_by_user()
    {
        $userNotAuthor = User::factory()->create();
        Sanctum::actingAs($userNotAuthor);

        $userAuthor = User::factory()->create();
        $film = Film::factory()->create();

        $comment = Comment::factory([
            'user_id' => $userAuthor->id,
            'film_id' => $film->id
        ])->create();

        $this->deleteJson(route('comments.destroy', $comment->id))
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }

    /**
     * Тест проверяет что автор комментария может его удалить
     *
     * @return void
     */
    public function test_can_delete_comment_by_author()
    {
        $userAuthor = User::factory()->create();
        Sanctum::actingAs($userAuthor);

        $film = Film::factory()->create();
        $comment = Comment::factory([
            'user_id' => $userAuthor->id,
            'film_id' => $film->id])
            ->create();

        $this->deleteJson(route('comments.destroy', $comment->id))
            ->assertOk()
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Тест проверяет что модератор может удалить любой комментарий, даже не являвшись его автором
     *
     * @return void
     */
    public function test_can_delete_comment_by_moderator()
    {
        $userModerator = User::factory()->moderator()->create();
        Sanctum::actingAs($userModerator);

        $userAuthor = User::factory()->create();
        $film = Film::factory()->create();

        $comment = Comment::factory([
            'user_id' => $userAuthor->id,
            'film_id' => $film->id
        ])->create();

        $this->deleteJson(route('comments.destroy', $comment->id))
        ->assertOk()
        ->assertJsonStructure([
            'data' => []
        ]);
    }
}
