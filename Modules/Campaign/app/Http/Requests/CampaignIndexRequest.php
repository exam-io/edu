<?php

namespace Modules\Campaign\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('campaign.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'in:draft,scheduled,launched,paused,completed'],
            'campaign_type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
