<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('media.update');
    }

    public function rules(): array
    {
        return [
            'visibility' => ['sometimes', 'string', 'in:private,public'],
            'status' => ['sometimes', 'string', 'in:active,archived'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
