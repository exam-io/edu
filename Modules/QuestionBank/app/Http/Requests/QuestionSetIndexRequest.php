<?php

namespace Modules\QuestionBank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionSetIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('question.bank.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:120'],
            'status' => ['sometimes', 'nullable', 'string', 'in:draft,published,archived'],
            'question_type' => ['sometimes', 'nullable', 'string', 'in:mixed,mcq,true_false,short_answer'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
