<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemEvent extends Model
{
    protected $fillable = ['title', 'content', 'target_roles', 'is_visible'];
    protected $casts = ['target_roles' => 'array'];
}