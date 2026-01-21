<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);

        return [
            'slug' => Str::slug($name),
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'image_url' => $this->faker->imageUrl(),
            'ingredients' => $this->faker->words(5),
            'steps' => $this->faker->sentences(5),
            'prep_time' => $this->faker->numberBetween(5, 120),
            'origin_country' => $this->faker->country(),
            'story' => $this->faker->paragraph(),
            'note' => $this->faker->sentence(),
            'pairings' => $this->faker->words(3),
            'is_alcoholic' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'published_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
