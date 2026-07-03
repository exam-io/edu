<?php

namespace Modules\Parent\Domain\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Student\Domain\Models\Student;
use Modules\Student\Domain\Models\StudentParent;

class ParentProfile extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'first_name',
        'last_name',
        'relationship',
        'phone',
        'email',
        'occupation',
        'address',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studentParents(): HasMany
    {
        return $this->hasMany(StudentParent::class, 'parent_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parents', 'parent_id', 'student_id')
            ->withPivot(['tenant_id', 'is_primary'])
            ->withTimestamps();
    }
}
