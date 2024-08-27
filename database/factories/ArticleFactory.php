<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'title' => fake()->unique()->sentence,
            'description' => fake()->paragraphs(8, true),
            'excerpt' => fake()->paragraphs(2, true),
            'is_published' => fake()->boolean,
            'min_to_read' => fake()->numberBetween(0, 100)
        ];
    }
}
