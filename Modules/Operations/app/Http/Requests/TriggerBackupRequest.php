<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TriggerBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('operations.backup.run');
    }

    public function rules(): array
    {
        return ['disk' => ['sometimes', 'string', 'max:40']];
    }
}
