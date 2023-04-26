<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Comment::factory()->count(30)->state(new Sequence(
            fn ($sequence) => ['user_id' => User::all()->random(),
                'film_id' => Film::all()->random()]
        ))->create();
    }
}
