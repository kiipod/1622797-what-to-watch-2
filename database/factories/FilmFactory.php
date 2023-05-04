<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'poster_image' => $this->faker->imageUrl(),
            'preview_image' => $this->faker->imageUrl(),
            'background_image' => $this->faker->imageUrl(),
            'background_color' => $this->faker->hexColor(),
            'released' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'run_time' => $this->faker->numberBetween(30, 200),
            'rating' => $this->faker->randomFloat(1, 3, 9),
            'scores_count' => $this->faker->numberBetween(5, 500),
            'video_link' => $this->faker->url(),
            'preview_video_link' => $this->faker->url(),
            'imdb_id' => 'tt' . $this->faker->unique()->randomNumber(7, true),
            'status' => $this->faker->randomElement(['pending', 'moderate', 'ready'])
        ];
    }
}
