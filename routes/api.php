
<?php

use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API yêu cầu đăng nhập bằng Sanctum - giữ nguyên.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API công khai dành cho React.
Route::get('/profile', [ProfileController::class, 'index']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/education', [EducationController::class, 'index']);
