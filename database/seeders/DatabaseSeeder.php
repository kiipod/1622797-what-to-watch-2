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
            DirectorSeeder::class,
            UserSeeder::class,
            FilmSeeder::class,
            CommentSeeder::class,
            FilmActorSeeder::class,
            FilmDirectorSeeder::class,
            FilmGenreSeeder::class,
            FavoriteSeeder::class
        ]);
    }
}
