<?php

namespace App\Observers;

use App\Models\CustomActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->logActivity('created', $model);
    }

    public function updated(Model $model): void
    {
        $this->logActivity('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->logActivity('deleted', $model);
    }

    protected function logActivity(string $actionType, Model $model): void
    {
        $modelName = class_basename($model);

        // 🛑 CRITICAL SAFETY GUARD: Prevent logging loop
        if ($modelName === 'CustomActivityLog') {
            return;
        }

        // 🌟 DYNAMIC GUARD CHECKING
        // Scans through Filament's multi-panel authentication instances explicitly
        $user = null;
        $guards = ['web', 'filament', 'api']; 
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                break;
            }
        }

        $recordId = $model->getKey();
        
        // Format human-readable role and operator context
        $roleContext = $user ? $user->role : 'system_guest';
        $roleLabel = $user ? str_replace('_', ' ', ucfirst($user->role)) : 'System/Guest';
        $operatorName = $user ? $user->name : 'System Engine';

        // Construct the payload summary narrative
        $summary = "User {$operatorName} ({$roleLabel}) successfully performed a baseline [{$actionType}] structural change on target {$modelName} entry (ID: {$recordId}).";

        CustomActivityLog::create([
            'user_id'                => $user?->id, // Captured cross-panel User ID
            'actor_role_context'     => $roleContext,
            'action_performed'       => $actionType,
            'target_resource_type'   => $modelName,
            'logged_payload_summary' => $summary,
        ]);
    }
}