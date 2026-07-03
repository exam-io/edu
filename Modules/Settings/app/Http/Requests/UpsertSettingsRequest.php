<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $requiredness = $this->isMethod('post') ? ['required'] : ['sometimes'];

        return [
            'language' => [...$requiredness, Rule::in(['en', 'hi'])],
            'theme' => [...$requiredness, Rule::in(['light', 'dark'])],
            'timezone' => [...$requiredness, 'timezone'],
        ];
    }
}
