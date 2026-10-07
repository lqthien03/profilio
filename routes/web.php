<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/login', 'auth.login')
    ->name('login');

Route::livewire('/admin', 'admin.dashboard')
    ->middleware('auth')
    ->name('admin.dashboard');

// Projects
Route::livewire('/admin/projects', 'admin.projects.index')
    ->middleware('auth')
    ->name('admin.projects.index');

Route::livewire('/admin/projects/create', 'admin.projects.create')
    ->middleware('auth')
    ->name('admin.projects.create');

Route::livewire('/admin/projects/{project}/edit', 'admin.projects.edit')
    ->middleware('auth')
    ->name('admin.projects.edit');

// Experiences
Route::livewire('/admin/experiences', 'admin.experiences.index')
    ->middleware('auth')
    ->name('admin.experiences.index');

Route::livewire('/admin/experiences/create', 'admin.experiences.create')
    ->middleware('auth')
    ->name('admin.experiences.create');
Route::livewire('/admin/experiences/{experience}/edit', 'admin.experiences.edit')
    ->middleware('auth')
    ->name('admin.experiences.edit');
