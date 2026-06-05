<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title',
        'content',
        'banner_image_path',
        'is_pinned_to_top',
        'target_roles',
        'is_visible'];
    protected $casts = ['target_roles' => 'array', 'is_pinned_to_top' => 'boolean', 'is_visible' => 'boolean'];
}