<?php

namespace Modules\Assessment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assessment_attempt.submit');
    }

    public function rules(): array
    {
        return [];
    }
}
