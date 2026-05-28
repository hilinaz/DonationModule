<?php

namespace Database\Factories;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donor>
 */
class DonorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'organization_name' => null,
            'donor_type' => 'individual',
            'category' => 'regular',
            'lifecycle_stage' => 'new',
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'preferred_channel' => 'email',
            'address' => fake()->address(),
            'interests' => ['health', 'education'],
            'engagement_score' => 0,
            'last_engaged_at' => null,
            'is_active' => true,
        ];
    }
}
