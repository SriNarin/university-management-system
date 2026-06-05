<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class TaskAssessment extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'task_type',
        'title',
        'file_attachment_path',
        'max_score_threshold',
        'deadline_cut_off',
        'qcm_blueprint',];

    protected $casts = [
        'qcm_blueprint' => 'array',
        'deadline_cut_off' => 'datetime',
    ];

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class, 'task_assessment_id');
    }
}