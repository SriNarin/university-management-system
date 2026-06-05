<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentSubmission extends Model
{
    protected $fillable = [
        'task_assessment_id',
        'student_id',
        'secured_score',
        'grade_letter', // This points to your actual database column layout
        'submission_notes',
        'attachment_file_path',
        'student_qcm_responses',
        'is_locked_by_office',
        'manager_approval_status',
    ];

    protected $casts = [
        'student_qcm_responses' => 'array',
        'secured_score' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($submission) {
            // Ensure we have a related task to prevent dividing by zero
            $maxScore = $submission->taskAssessment?->max_score_threshold ?? 100;
            
            // Calculate the true percentage based on the task's max score threshold
            $pct = $maxScore > 0 ? ($submission->secured_score / $maxScore) * 100 : 0; 
            
            // 🌟 FIXED: Map directly to your actual column 'grade_letter'
            $submission->grade_letter = match(true) {
                $pct >= 95 => 'A+',
                $pct >= 90 => 'A',
                $pct >= 85 => 'B+',
                $pct >= 80 => 'B',
                $pct >= 75 => 'C+',
                $pct >= 70 => 'C',
                $pct >= 65 => 'D+',
                $pct >= 60 => 'D',
                $pct >= 50 => 'E',
                default    => 'F'
            };
        });
    }

    public function taskAssessment(): BelongsTo
    {
        return $this->belongsTo(TaskAssessment::class, 'task_assessment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}