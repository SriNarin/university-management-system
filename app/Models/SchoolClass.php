<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = ['academic_structure_id', 'class_code','semester', 'shift','room_number','is_timetable_published', 'timetable_published_at'];
    public function academicStructure(): BelongsTo
    {
        return $this->belongsTo(AcademicStructure::class, 'academic_structure_id');
    }
    public function students() { return $this->belongsToMany(User::class, 'class_user', 'school_class_id', 'user_id'); }
    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'school_class_id');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'school_class_id', 'user_id')
                    ->withPivot(['enrollment_type', 'scholarship_type', 'amount_paid', 'approval_status', 'approved_by_manager_id'])
                    ->withTimestamps();
    }
    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'school_class_id');
    }

    public function enrollments(): HasMany
    {
        // Adjust 'ClassEnrollment::class' to match whatever your enrollment model is named
        return $this->hasMany(ClassUser::class, 'school_class_id');
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    

}