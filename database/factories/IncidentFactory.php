<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident>
 */
class IncidentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'zone_id' => Zone::factory(),
            'type' => fake()->randomElement(['Fuite', 'Contamination', "Coupure d'eau", 'Équipement hors ligne']),
            'status' => fake()->randomElement(['reported', 'in_progress', 'resolved']),
            'description' => fake()->sentence(8),
            'location' => fake()->streetAddress(),
            'reported_at' => now()->subHours(fake()->numberBetween(1, 200)),
        ];
    }
}
