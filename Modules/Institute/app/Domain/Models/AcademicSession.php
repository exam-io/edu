<?php

namespace Modules\Institute\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Institute\Domain\Enums\AcademicSessionStatus;

class AcademicSession extends Model
{
    use HasFactory;

    protected $table = 'academic_sessions';

    protected $fillable = [
        'institute_id',
        'name',
        'code',
        'starts_on',
        'ends_on',
        'is_current',
        'status',
        'metadata',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_current' => 'boolean',
            'status' => AcademicSessionStatus::class,
            'metadata' => 'array',
        ];
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(AcademicClass::class, 'academic_session_id');
    }
}
