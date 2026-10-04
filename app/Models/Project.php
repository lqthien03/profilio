<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    // $fillable giúp Laravel biết những field nào được phép mass assignment.
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'demo_url',
        'repository_url',
        'featured',
        'status',
        'sort_order',
        'published_at',
    ];
    // casts
    // Ví dụ database trả: 
    // featured = 1
    // Laravel sẽ chuyển thành: true và published_at sẽ trở thành đối tượng date/datetime phù hợp.
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }
}
