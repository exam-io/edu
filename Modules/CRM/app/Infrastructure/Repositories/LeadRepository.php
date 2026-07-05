<?php

namespace Modules\CRM\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Application\Contracts\LeadRepositoryInterface;
use Modules\CRM\Domain\Models\Lead;

class LeadRepository implements LeadRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = Lead::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['source'])) {
            $builder->where('source', (string) $filters['source']);
        }

        if (! empty($filters['search'])) {
            $q = (string) $filters['search'];
            $builder->where(function ($query) use ($q): void {
                $query->where('first_name', 'like', '%' . $q . '%')
                    ->orWhere('last_name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('interest', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }

    public function findForTenant(int $tenantId, int $id): ?Lead
    {
        return Lead::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function create(array $attributes): Lead
    {
        return Lead::query()->create($attributes);
    }

    public function update(Lead $lead, array $attributes): Lead
    {
        $lead->fill($attributes)->save();

        return $lead->refresh();
    }

    public function delete(Lead $lead): void
    {
        $lead->delete();
    }

    public function createActivity(int $tenantId, int $leadId, string $type, string $message, ?int $actorUserId = null): void
    {
        DB::table('crm_lead_activities')->insert([
            'tenant_id' => $tenantId,
            'lead_id' => $leadId,
            'activity_type' => $type,
            'message' => $message,
            'actor_user_id' => $actorUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
