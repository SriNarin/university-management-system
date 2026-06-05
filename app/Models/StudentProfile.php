<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = ['user_id', 'student_id_card', 'age', 'date_of_birth', 'gender', 'phone_number', 'current_address'];
    public function user() { return $this->belongsTo(User::class); }
}