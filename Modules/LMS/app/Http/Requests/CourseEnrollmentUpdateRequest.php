<?php

namespace Modules\LMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseEnrollmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('lms.enrollment.update');
    }

    public function rules(): array
    {
        return [
            'enrolled_at' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'in:active,paused,completed,dropped'],
        ];
    }
}
