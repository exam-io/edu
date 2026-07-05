<?php

namespace Modules\Communication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('communication.announcement.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'body' => ['required', 'string'],
            'target_user_ids' => ['required', 'array', 'min:1'],
            'target_user_ids.*' => ['integer', 'exists:users,id'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['string', 'in:in_app,email,sms,push'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'publish_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
