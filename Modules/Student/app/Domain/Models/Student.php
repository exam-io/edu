<?php

namespace Modules\Student\Domain\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enrollment\Domain\Models\StudentEnrollment;
use Modules\Parent\Domain\Models\ParentProfile;

class Student extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'admission_no',
        'roll_no',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'blood_group',
        'phone',
        'email',
        'photo',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'emergency_contact',
        'admission_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function studentParents(): HasMany
    {
        return $this->hasMany(StudentParent::class, 'student_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'student_parents', 'student_id', 'parent_id')
            ->withPivot(['tenant_id', 'is_primary'])
            ->withTimestamps();
    }
}
