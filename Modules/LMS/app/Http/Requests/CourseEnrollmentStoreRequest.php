<?php

namespace Modules\LMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseEnrollmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('lms.enrollment.create');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'min:1'],
            'student_id' => ['required', 'integer', 'min:1'],
            'enrolled_at' => ['required', 'date'],
            'status' => ['required', 'string', 'in:active,paused,completed,dropped'],
        ];
    }
}
