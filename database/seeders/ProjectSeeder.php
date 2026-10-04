<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::query()->delete();

        Project::factory()->create([
            'title' => 'Portfolio Website',
            'slug' => 'portfolio-website',
            'short_description' => 'Personal portfolio website built with Laravel and React.',
            'status' => 'published',
            'featured' => true,
            'sort_order' => 1,
            'published_at' => now(),
        ]);

        Project::factory()->create([
            'title' => 'E-commerce Platform',
            'slug' => 'e-commerce-platform',
            'short_description' => 'Modern e-commerce platform with product and order management.',
            'status' => 'published',
            'featured' => true,
            'sort_order' => 2,
            'published_at' => now(),
        ]);

        Project::factory()->create([
            'title' => 'Real Estate Management',
            'slug' => 'real-estate-management',
            'short_description' => 'Real estate management system for properties, categories and listings.',
            'status' => 'published',
            'featured' => false,
            'sort_order' => 3,
            'published_at' => now(),
        ]);

        Project::factory()->create([
            'title' => 'Booking System',
            'slug' => 'booking-system',
            'short_description' => 'Online booking platform with schedule and reservation management.',
            'status' => 'published',
            'featured' => false,
            'sort_order' => 4,
            'published_at' => now(),
        ]);

        Project::factory()->create([
            'title' => 'Laravel CMS',
            'slug' => 'laravel-cms',
            'short_description' => 'Content management system built with Laravel and Livewire.',
            'status' => 'draft',
            'featured' => false,
            'sort_order' => 5,
        ]);

        Project::factory()->count(5)->create();
    }
}
