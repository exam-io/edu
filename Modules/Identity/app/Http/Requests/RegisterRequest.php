<?php

namespace Modules\Identity\Http\Requests;

class RegisterRequest extends ApiFormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'language' => ['sometimes', 'string', 'max:12'],
            'theme' => ['sometimes', 'string', 'max:32'],
            'timezone' => ['sometimes', 'string', 'max:64'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'language' => $this->input('language', 'en'),
            'theme' => $this->input('theme', 'light'),
            'timezone' => $this->input('timezone', 'UTC'),
        ]);
    }
}
