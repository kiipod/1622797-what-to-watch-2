<?php

namespace Database\Seeders;

use App\Models\Director;
use App\Models\Film;
use App\Models\FilmDirector;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FilmDirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        FilmDirector::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['film_id' => Film::all()->random(),
                'director_id' => Director::all()->random()]
        ))->create();
    }
}
