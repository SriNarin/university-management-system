<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonMaterial extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'lecture_title_topic',
        'resource_attachment_path',
        'is_visible_to_students',];
    protected $casts = ['resource_attachment_path' => 'array', 'is_visible_to_students' => 'boolean',];
    
    public function schoolClass() { return $this->belongsTo(SchoolClass::class); }

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }
}