<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueueOpsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('operations.queue.manage');
    }

    public function rules(): array
    {
        return [
            'operation' => ['required', 'string', 'max:120'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
