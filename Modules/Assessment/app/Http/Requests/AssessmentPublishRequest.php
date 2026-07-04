<?php

namespace Modules\Assessment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentPublishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assessment.publish');
    }

    public function rules(): array
    {
        return [];
    }
}
