<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::livewire('/admin', 'admin.dashboard')
    ->name('admin.dashboard');
