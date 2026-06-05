<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassUser extends Pivot
{
    protected $table = 'class_user';

    public $incrementing = true; // Required since we added an auto-incrementing ID row

    protected $fillable = [
        'school_class_id',
        'user_id',
        'enrollment_type',
        'scholarship_type',
        'amount_paid',
        'approval_status',
        'approved_by_manager_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function managerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_manager_id');
    }
}