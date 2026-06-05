<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['department_id', 'subject_code', 'title_en', 'title_kh', 'credits'];
    public function department() { return $this->belongsTo(Department::class); }
}