<?php

namespace Modules\Admissions\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Admissions\Application\Contracts\AdmissionApplicationRepositoryInterface;
use Modules\CRM\Domain\Events\LeadStatusChanged;
use Modules\CRM\Domain\Models\Lead;

class CreateApplicationFromQualifiedLead implements ShouldQueue
{
    public function __construct(
        private readonly AdmissionApplicationRepositoryInterface $repository,
    ) {}

    public function handle(LeadStatusChanged $event): void
    {
        if ($event->newStatus !== 'qualified') {
            return;
        }

        $exists = $this->repository->firstByLead($event->tenantId, $event->leadId);
        if ($exists !== null) {
            return;
        }

        $lead = Lead::query()
            ->where('tenant_id', $event->tenantId)
            ->find($event->leadId);

        if ($lead === null) {
            return;
        }

        $this->repository->create([
            'tenant_id' => $event->tenantId,
            'lead_id' => $lead->id,
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'program' => $lead->interest,
            'source' => 'crm_auto',
            'status' => 'submitted',
            'notes' => 'Auto-created from qualified lead.',
            'submitted_at' => now(),
        ]);
    }
}
