<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomActivityLog extends Model
{
    protected $fillable = ['user_id', 'actor_role_context', 'action_performed', 'target_resource_type', 'logged_payload_summary'];
    public function user() { return $this->belongsTo(User::class); }
}