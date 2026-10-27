<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::query()
            ->orderBy('sort_order')
            ->orderByDesc('start_date')
            ->get([
                'id',
                'company',
                'position',
                'location',
                'employment_type',
                'description',
                'start_date',
                'end_date',
                'is_current',
                'sort_order',
            ]);

        return response()->json([
            'data' => $experiences,
        ]);
    }
}
