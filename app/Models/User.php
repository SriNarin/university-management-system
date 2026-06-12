<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'is_active', 
        'lang_preference', 
        'avatar_url',
        'permissions_matrix'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'permissions_matrix' => 'array',
    ];

    /**
     * Filament Multi-Panel Route Authorization Filter Guard Interceptor.
     * Restricts physical interface terminal access based on database structural roles.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Global administrative lock: Terminate execution if account is deactivated
        if (!$this->is_active) {
            return false;
        }

        return match($panel->getId()) {
            'admin'   => $this->role === 'admin',
            'faculty' => $this->role === 'faculty_manager',
            'office'  => $this->role === 'study_office',
            'teacher' => $this->role === 'teacher',
            'student' => $this->role === 'student',
            default   => false,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | System Domain Relationship Structures Matrix
    |--------------------------------------------------------------------------
    */

    /**
     * One-to-One connection mapping if this account manages a structural faculty branch.
     */
    public function faculty() 
    { 
        return $this->hasOne(Faculty::class, 'manager_id'); 
    }

    /**
     * One-to-One profile registry container attachment if the user is a student.
     */
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * Pivot-table connection assigning users directly to dedicated classroom sections.
     */
    public function classes() 
    { 
        return $this->belongsToMany(SchoolClass::class, 'class_user', 'user_id', 'school_class_id'); 
    }

    /**
     * Collection ledger of tracking items logged against this student account.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * File upload and compilation assessment outputs committed by this user.
     */
   public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Granular Access Verification Logic API
    |--------------------------------------------------------------------------
    */

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        return is_array($this->permissions_matrix) && in_array($permission, $this->permissions_matrix);
    }

       

    public function enrolledClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_user', 'user_id', 'school_class_id')
            ->withPivot(['enrollment_type', 'approval_status'])
            ->withTimestamps();
    }

    public function schoolClasses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->belongsToMany(SchoolClass::class, 'class_user', 'user_id', 'school_class_id')
                ->withPivot(['enrollment_type', 'scholarship_type', 'amount_paid', 'approval_status'])
                ->withTimestamps();
}

      public function getFilamentAvatarUrl(): ?string
        {
           
            // 1. Check if the user has an avatar path stored and if the file actually exists on the public disk
            if ($this->avatar_url && Storage::disk('public')->exists($this->avatar_url)) {
                return Storage::disk('public')->Storage::url($this->avatar_url);
            }


            // 2. Strong Fallback: If no file exists or it's missing, use the working UI-Avatars API link
            return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($this->name);
        }
}