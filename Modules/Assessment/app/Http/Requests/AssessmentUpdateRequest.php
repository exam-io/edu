<?php

namespace Modules\Assessment\Http\Requests;

class AssessmentUpdateRequest extends AssessmentStoreRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assessment.update');
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $rules['title'][0] = 'sometimes';
        $rules['type'][0] = 'sometimes';

        return $rules;
    }
}
