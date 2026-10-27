<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'platform' => fake()->randomElement([
                'facebook',
                'instagram',
                'github',
                'linkedin',
            ]),
            'label' => null,
            'url' => 'https://example.com',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
