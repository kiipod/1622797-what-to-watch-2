<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Comment;
use App\Models\Director;
use App\Models\Favorite;
use App\Models\Film;
use App\Models\FilmActor;
use App\Models\FilmDirector;
use App\Models\FilmGenre;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Actor::factory()->count(30)->create();
        Film::factory()->count(30)->create();
        User::factory()->count(30)->create();
        Genre::factory()->count(30)->create();
        Director::factory()->count(30)->create();

        FilmActor::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['film_id' => Film::all()->random(),
                'actor_id' => Actor::all()->random()]
        ))->create();

        FilmGenre::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['film_id' => Film::all()->random(),
                'genre_id' => Genre::all()->random()]
        ))->create();

        FilmDirector::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['film_id' => Film::all()->random(),
                'director_id' => Director::all()->random()]
        ))->create();

        Comment::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['user_id' => User::all()->random(),
                'film_id' => Film::all()->random()]
        ))->create();

        Favorite::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['user_id' => User::all()->random(),
                'film_id' => Film::all()->random()]
        ))->create();
    }
}
