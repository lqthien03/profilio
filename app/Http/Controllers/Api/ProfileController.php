<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::with([
            'socialLinks' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            },
        ])->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'name' => $profile->name,
                'headline' => $profile->headline,
                'about' => $profile->about,
                'email' => $profile->email,
                'phone' => $profile->phone,
                'address' => $profile->address,
                'avatar' => $profile->avatar
                    ? Storage::disk('public')->url($profile->avatar)
                    : null,
                'resume_url' => $profile->resume_url,
                'website_url' => $profile->website_url,
                'social_links' => $profile->socialLinks->map(
                    function ($link) {
                        return [
                            'platform' => $link->platform,
                            'label' => $link->label,
                            'url' => $link->url,
                        ];
                    }
                )->values(),
            ],
        ]);
    }
}
