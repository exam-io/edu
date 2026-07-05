<?php

namespace Modules\Admissions\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Admissions\Application\Contracts\AdmissionApplicationRepositoryInterface;
use Modules\Admissions\Domain\Models\AdmissionApplication;

class AdmissionApplicationRepository implements AdmissionApplicationRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = AdmissionApplication::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['program'])) {
            $builder->where('program', (string) $filters['program']);
        }

        if (! empty($filters['search'])) {
            $q = (string) $filters['search'];
            $builder->where(function ($query) use ($q): void {
                $query->where('first_name', 'like', '%' . $q . '%')
                    ->orWhere('last_name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('program', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }

    public function findForTenant(int $tenantId, int $id): ?AdmissionApplication
    {
        return AdmissionApplication::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function firstByLead(int $tenantId, int $leadId): ?AdmissionApplication
    {
        return AdmissionApplication::query()
            ->where('tenant_id', $tenantId)
            ->where('lead_id', $leadId)
            ->first();
    }

    public function create(array $attributes): AdmissionApplication
    {
        return AdmissionApplication::query()->create($attributes);
    }

    public function update(AdmissionApplication $application, array $attributes): AdmissionApplication
    {
        $application->fill($attributes)->save();

        return $application->refresh();
    }

    public function delete(AdmissionApplication $application): void
    {
        $application->delete();
    }

    public function logStatusHistory(int $tenantId, int $applicationId, ?string $fromStatus, string $toStatus, ?int $actorUserId = null): void
    {
        DB::table('admission_application_status_histories')->insert([
            'tenant_id' => $tenantId,
            'admission_application_id' => $applicationId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'note' => null,
            'actor_user_id' => $actorUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
