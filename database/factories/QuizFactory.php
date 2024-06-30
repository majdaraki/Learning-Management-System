<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory()->create(),
            'quiz_name' => fake()->name(),
            'after_video' => fake()->numberBetween(1, 5),
            'timer' => fake()->numberBetween(10, 60),
        ];
    }
}
