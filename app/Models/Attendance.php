<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['class_schedule_id', 'student_id', 'teaching_date', 'status'];
   public function classSchedule():BelongsTo
{
    // Adjust 'class_schedule_id' if your foreign key column uses a different name
    return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
}
    public function student() { return $this->belongsTo(User::class, 'student_id'); }

    public function attendanceSession(): BelongsTo
    {
        // Adjust 'attendance_session_id' if your foreign key column uses a different name (e.g., class_schedule_id)
        return $this->belongsTo(ClassSchedule::class, 'attendance_session_id'); 
    }
    
}