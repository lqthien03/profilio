<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(5);
        return [
            'title' => $title,

            'slug' => str($title)->slug()->toString(),

            'short_description' => fake()->sentence(),

            'description' => fake()->paragraphs(3, true),

            'thumbnail' => null,

            'demo_url' => fake()->optional()->url(),

            'repository_url' => fake()->optional()->url(),

            'featured' => fake()->boolean(20),

            'status' => fake()->randomElement(['draft', 'published',]),

            'sort_order' => fake()->numberBetween(0, 10),

            'published_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
