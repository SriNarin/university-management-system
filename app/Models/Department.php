<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['faculty_id', 'name_en', 'name_kh'];
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
    public function academicStructures(): HasMany
    {
        return $this->hasMany(AcademicStructure::class);
    }

    public function schoolClasses(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
{
    return $this->hasManyThrough(
        SchoolClass::class,
        AcademicStructure::class,
        'department_id',           // Foreign key on academic_structures table
        'academic_structure_id',   // Foreign key on school_classes table
        'id',                      // Local key on departments table
        'id'                       // Local key on academic_structures table
    );
}
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}