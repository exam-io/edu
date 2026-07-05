<?php

namespace Modules\Campaign\Http\Requests;

class CampaignUpdateRequest extends CampaignStoreRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('campaign.update');
    }
}
