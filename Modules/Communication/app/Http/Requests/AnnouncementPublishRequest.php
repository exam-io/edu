<?php

namespace Modules\Communication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementPublishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('communication.announcement.publish');
    }

    public function rules(): array
    {
        return [];
    }
}
