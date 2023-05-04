<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Film;
use App\Models\FilmActor;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FilmActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        FilmActor::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['film_id' => Film::all()->random(),
                'actor_id' => Actor::all()->random()]
        ))->create();
    }
}
