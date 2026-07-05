<?php

namespace Modules\LiveClass\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Section;
use Modules\Academic\Domain\Models\Subject;
use Modules\Shared\Domain\Models\TenantAwareModel;

class LiveClassSession extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'host_user_id',
        'class_id',
        'section_id',
        'subject_id',
        'provider',
        'provider_meeting_id',
        'room_name',
        'meeting_url',
        'meeting_password',
        'scheduled_start_at',
        'scheduled_end_at',
        'actual_start_at',
        'actual_end_at',
        'attendance_policy',
        'max_participants',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_start_at' => 'datetime',
            'scheduled_end_at' => 'datetime',
            'actual_start_at' => 'datetime',
            'actual_end_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'host_user_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(LiveClassAttendance::class, 'live_class_session_id');
    }
}
