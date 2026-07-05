<?php

namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthChecksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('system.health.view');
    }

    public function rules(): array
    {
        return ['refresh' => ['sometimes', 'boolean']];
    }
}
