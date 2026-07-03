<?php

namespace Modules\ContentProcessing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentSourceIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('content.processing.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:120'],
            'status' => ['sometimes', 'nullable', 'string', 'in:queued,processing,processed,failed'],
            'source_type' => ['sometimes', 'nullable', 'string', 'in:upload,url,text'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
