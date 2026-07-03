<?php

namespace Modules\Student\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Parent\Domain\Models\ParentProfile;

class StudentParent extends Model
{
    use HasFactory;

    protected $table = 'student_parents';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'parent_id',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
}
