<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Your Name',
                'headline' => 'Web Developer',
                'about' => 'I build modern web applications.',
                'email' => null,
                'phone' => null,
                'address' => null,
                'avatar' => null,
                'resume_url' => null,
                'website_url' => null,
            ]
        );
    }
}
