<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::firstOrCreate(
            ['school' => 'FPT Polytechnic'],
            [
                'degree' => 'Cao đẳng',
                'field_of_study' => 'Công nghệ thông tin',
                'description' => 'Học tập và thực hành phát triển ứng dụng web.',
                'start_date' => '2022-09-01',
                'end_date' => '2025-06-30',
                'is_current' => false,
                'location' => 'Việt Nam',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );
    }
}
