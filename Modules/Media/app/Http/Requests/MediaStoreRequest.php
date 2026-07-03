<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('media.create');
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:51200', 'mimetypes:image/jpeg,image/png,image/webp,application/pdf,video/mp4,text/plain,application/zip'],
            'disk' => ['sometimes', 'string', 'in:local,public'],
            'visibility' => ['sometimes', 'string', 'in:private,public'],
        ];
    }
}
