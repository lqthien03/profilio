<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('status', 'published')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $projects->map(
                fn(Project $project) => $this->formatProject($project)
            ),
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$project) {
            return response()->json([
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'data' => $this->formatProject($project),
        ]);
    }

    private function formatProject(Project $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'short_description' => $project->short_description,
            'description' => $project->description,
            'thumbnail' => $project->thumbnail
                ? Storage::disk('public')->url($project->thumbnail)
                : null,
            'demo_url' => $project->demo_url,
            'repository_url' => $project->repository_url,
            'featured' => $project->featured,
            'published_at' => $project->published_at,
        ];
    }
}
