<?php

namespace Modules\Campaign\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignLaunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('campaign.launch');
    }

    public function rules(): array
    {
        return [];
    }
}
