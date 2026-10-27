<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;

class EducationController extends Controller
{
    public function index()
    {
        $education = Education::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('start_date')
            ->get([
                'id',
                'school',
                'degree',
                'field_of_study',
                'description',
                'start_date',
                'end_date',
                'is_current',
                'location',
                'sort_order',
            ]);

        return response()->json([
            'data' => $education,
        ]);
    }
}
