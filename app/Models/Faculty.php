<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faculty extends Model
{
    use HasFactory;
    protected $fillable = ['name_en', 'name_kh', 'manager_id', 'is_active' ];
    public function faculty_manager() { return $this->belongsTo(User::class, 'user_id'); }
    public function departments() { return $this->hasMany(Department::class); }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}