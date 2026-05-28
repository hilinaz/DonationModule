<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'code' => strtoupper(fake()->unique()->bothify('CMP###??')),
            'description' => fake()->paragraph(),
            'type' => 'general',
            'goal_amount' => fake()->numberBetween(5000, 500000),
            'goal_currency' => 'USD',
            'status' => 'active',
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->addDays(30),
            'is_public' => true,
        ];
    }
}
