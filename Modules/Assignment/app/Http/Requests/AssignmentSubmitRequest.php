<?php

namespace Modules\Assignment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assignment.submit');
    }

    public function rules(): array
    {
        return [
            'file_path' => ['required', 'string', 'max:500'],
        ];
    }
}
