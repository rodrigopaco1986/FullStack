<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Monster>
 */
class MonsterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'attack' => fake()->numberBetween($min = 10, $max = 100),
            'defense' => fake()->numberBetween($min = 10, $max = 100),
            'hp' => fake()->numberBetween($min = 10, $max = 100),
            'speed' => fake()->numberBetween($min = 10, $max = 100),
            'imageUrl' => fake()->imageUrl($width = 500, $height = 500, 'monsters'),
        ];
    }
}
