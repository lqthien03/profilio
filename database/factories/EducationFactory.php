<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school' => fake()->randomElement([
                'FPT Polytechnic',
                'Cao đẳng FPT Polytechnic',
            ]),
            'degree' => 'Cao đẳng',
            'field_of_study' => 'Công nghệ thông tin',
            'description' => fake()->paragraph(),
            'start_date' => fake()->dateTimeBetween('-5 years', '-2 years'),
            'end_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'is_current' => false,
            'location' => 'Việt Nam',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
