<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        Skill::create([
            'name' => 'Laravel',
            'category' => 'Backend',
            'level' => 90,
            'icon' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'PHP',
            'category' => 'Backend',
            'level' => 85,
            'icon' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'MySQL',
            'category' => 'Database',
            'level' => 80,
            'icon' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'JavaScript',
            'category' => 'Frontend',
            'level' => 80,
            'icon' => null,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'React',
            'category' => 'Frontend',
            'level' => 75,
            'icon' => null,
            'sort_order' => 5,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'Tailwind CSS',
            'category' => 'Frontend',
            'level' => 85,
            'icon' => null,
            'sort_order' => 6,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'Git',
            'category' => 'Tools',
            'level' => 85,
            'icon' => null,
            'sort_order' => 7,
            'is_active' => true,
        ]);

        Skill::create([
            'name' => 'REST API',
            'category' => 'Backend',
            'level' => 80,
            'icon' => null,
            'sort_order' => 8,
            'is_active' => true,
        ]);
    }
}
