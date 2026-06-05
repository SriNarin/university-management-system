<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectFinalGrade extends Model
{
    // Allow mass assignment for the fields we are tracking
    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'total_accumulated_score',
        'final_grade_letter',
        'is_submitted_to_office',
        'submitted_to_office_at',
        'is_approved_by_manager',
        'approved_by_manager_at',
    ];

    protected $casts = [
        'is_submitted_to_office' => 'boolean',
        'is_approved_by_manager' => 'boolean',
        'submitted_to_office_at' => 'datetime',
        'approved_by_manager_at' => 'datetime',
        'total_accumulated_score' => 'decimal:2',
    ];

    /**
     * Relationship to the Student (User model)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relationship to the Class Schedule slot
     */
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function academicStructure(): BelongsTo
    {
        return $this->belongsTo(AcademicStructure::class, 'academic_structure_id');
    }
}