<?php

namespace Modules\QuestionBank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionSetUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('question.bank.update');
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'question_type' => ['sometimes', 'string', 'in:mixed,mcq,true_false,short_answer'],
            'difficulty' => ['sometimes', 'string', 'in:easy,medium,hard'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
