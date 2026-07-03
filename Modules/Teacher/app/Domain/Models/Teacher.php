<?php

namespace Modules\Teacher\Domain\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enrollment\Domain\Models\TeacherAssignment;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'employee_no',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'phone',
        'email',
        'photo',
        'qualification',
        'specialization',
        'joining_date',
        'experience_years',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }
}
