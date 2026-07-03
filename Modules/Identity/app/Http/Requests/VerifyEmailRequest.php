<?php

namespace Modules\Identity\Http\Requests;

class VerifyEmailRequest extends ApiFormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'hash' => ['required', 'string', 'size:40'],
        ];
    }
}
