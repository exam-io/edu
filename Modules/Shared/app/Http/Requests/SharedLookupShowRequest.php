<?php

namespace Modules\Shared\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SharedLookupShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['students', 'teachers', 'parents', 'classes', 'sections', 'batches', 'subjects', 'sessions'])],
        ];
    }
}
