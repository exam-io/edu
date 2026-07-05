<?php

namespace Modules\Communication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunicationHistoryIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('communication.history.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'in:pending,sent,failed'],
            'channel' => ['sometimes', 'nullable', 'string', 'in:in_app,email,sms,push'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
