<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Laravel',
                'PHP',
                'MySQL',
                'JavaScript',
                'React',
                'Tailwind CSS',
                'Git',
            ]),

            'category' => fake()->randomElement([
                'Backend',
                'Frontend',
                'Database',
                'Tools',
            ]),

            'level' => fake()->numberBetween(70, 95),

            'icon' => null,

            'sort_order' => fake()->numberBetween(1, 20),

            'is_active' => true,
        ];
    }
}
