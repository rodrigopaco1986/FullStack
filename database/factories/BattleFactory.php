<?php

namespace Database\Factories;

use App\Models\Monster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Battle>
 */
class BattleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        Monster::factory()->create();

        return [
            'monsterA' => Monster::first()->id,
            'monsterB' => Monster::first()->id,
            'winner' => Monster::first()->id,
        ];
    }
}
