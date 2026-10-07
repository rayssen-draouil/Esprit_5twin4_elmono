<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zone>
 */
class ZoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Zone '.fake()->unique()->city(),
            'address' => fake()->address(),
            'description' => fake()->sentence(12),
            'risk_level' => fake()->randomElement(['low', 'medium', 'high']),
        ];
    }
}
