<?php

namespace Modules\Campaign\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('campaign.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'campaign_type' => ['sometimes', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['string', 'in:in_app,email,sms,push'],
            'recipient_user_ids' => ['required', 'array', 'min:1'],
            'recipient_user_ids.*' => ['integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', 'in:draft,scheduled,launched,paused,completed'],
            'scheduled_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
