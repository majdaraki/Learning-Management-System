<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'category_id' => fake()->numberBetween(3,7),
            'teacher_id' => fake()->numberBetween(3,10),
            'description' => fake()->paragraph(3),
            'total_likes' => fake()->numberBetween(1,50),
        ];
    }
}
