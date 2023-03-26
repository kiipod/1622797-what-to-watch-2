<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\Models\Comment;
use App\Models\Director;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест связей между моделями
     *
     * @return void
     */
    public function test_relationship_model(): void
    {
        $databaseSeeder = new DatabaseSeeder();
        $databaseSeeder->run();

        $actor = Actor::all()->random();
        $film = Film::all()->random();
        $genre = Genre::all()->random();
        $user = User::all()->random();
        $director = Director::all()->random();

        // Проверка связи актёр-фильм
        foreach ($actor->films as $film) {
            $this->assertInstanceOf(Film::class, $film);
        }

        // Проверка связи режиссер-фильм
        foreach ($director->films as $film) {
            $this->assertInstanceOf(Film::class, $film);
        }

        // Проверка связи фильм-пользователи
        foreach ($film->users as $user) {
            $this->assertInstanceOf(User::class, $user);
        }

        // Проверка связи фильм-жанры
        foreach ($film->genres as $genre) {
            $this->assertInstanceOf(Genre::class, $genre);
        }

        // Проверка связи фильм-актёры
        foreach ($film->actors as $actor) {
            $this->assertInstanceOf(Actor::class, $actor);
        }

        // Проверка связи фильм-режиссер
        foreach ($film->directors as $director) {
            $this->assertInstanceOf(Director::class, $director);
        }

        // Проверка связи фильм-комментарии
        foreach ($film->comments as $comment) {
            $this->assertInstanceOf(Comment::class, $comment);
        }

        // Проверка связи жанр-фильмы
        foreach ($genre->films as $film) {
            $this->assertInstanceOf(Film::class, $film);
        }

        // Проверка связи пользователь-комментарии
        foreach ($user->comments as $comment) {
            $this->assertInstanceOf(Comment::class, $comment);
        }

        // Проверка связи пользователь-избранное
        foreach ($user->favorites as $favorite) {
            $this->assertInstanceOf(Film::class, $favorite);
        }
    }
}
