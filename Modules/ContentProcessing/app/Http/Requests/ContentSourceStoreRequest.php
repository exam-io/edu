<?php

namespace Modules\ContentProcessing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentSourceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('content.processing.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'source_type' => ['required', 'string', 'in:upload,url,text'],
            'file' => ['required_if:source_type,upload', 'nullable', 'file', 'max:51200', 'mimetypes:text/plain,application/pdf'],
            'source_url' => ['required_if:source_type,url', 'nullable', 'url', 'max:2048'],
            'raw_text' => ['required_if:source_type,text', 'nullable', 'string', 'max:200000'],
        ];
    }
}
