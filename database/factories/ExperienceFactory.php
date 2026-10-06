<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 years', '-1 year');
        $isCurrent = fake()->boolean(30);

        return [
            'company' => fake()->company(),

            'position' => fake()->randomElement([
                'Backend Developer',
                'Full Stack Developer',
                'Laravel Developer',
                'Frontend Developer',
                'Software Developer',
                'Web Developer',
            ]),

            'location' => fake()->randomElement([
                'Ho Chi Minh City',
                'Hanoi',
                'Da Nang',
                'Remote',
            ]),

            'employment_type' => fake()->randomElement([
                'Full-time',
                'Part-time',
                'Freelance',
                'Internship',
            ]),

            'description' => fake()->paragraphs(2, true),

            'start_date' => $startDate,

            'end_date' => $isCurrent
                ? null
                : fake()->dateTimeBetween(
                    $startDate,
                    'now'
                ),

            'is_current' => $isCurrent,

            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
