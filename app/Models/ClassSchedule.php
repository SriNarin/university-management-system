<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    protected $fillable = [
        'academic_structure_id',
        'school_class_id', 
        'subject_id', 
        'subject_code',
        'subject_name_en', 
        'teacher_id', 
        'day_of_week', 
        'start_time', 
        'end_time'];

    public function academicStructure(): BelongsTo
    {
        return $this->belongsTo(AcademicStructure::class, 'academic_structure_id');
    }
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
   public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}