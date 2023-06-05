<?php

namespace Database\Seeders;

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
        $this->call([
            ActorSeeder::class,
            GenreSeeder::class,
            UserSeeder::class,
            FilmSeeder::class,
            CommentSeeder::class,
            FilmActorSeeder::class,
            FilmGenreSeeder::class,
            FavoriteSeeder::class
        ]);
    }
}
