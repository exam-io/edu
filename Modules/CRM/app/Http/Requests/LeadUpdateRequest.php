<?php

namespace Modules\CRM\Http\Requests;

class LeadUpdateRequest extends LeadStoreRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('crm.lead.update');
    }
}
