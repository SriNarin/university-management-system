<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicStructure extends Model
{

    protected $table = 'academic_structures';

    protected $fillable = ['department_id', 'generation', 'academic_level', 'year_progress',  ];
   public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'academic_structure_id');
    }

}