<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'headline' => 'Web Developer',
            'about' => fake()->paragraph(),
            'email' => fake()->safeEmail(),
            'phone' => null,
            'address' => fake()->city(),
            'avatar' => null,
            'resume_url' => null,
            'website_url' => null,
        ];
    }
}
