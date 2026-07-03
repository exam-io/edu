<?php

namespace Modules\QuestionBank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionSetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('question.bank.create');
    }

    public function rules(): array
    {
        return [
            'ai_generation_request_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'content_source_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'question_type' => ['sometimes', 'string', 'in:mixed,mcq,true_false,short_answer'],
            'difficulty' => ['sometimes', 'string', 'in:easy,medium,hard'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'meta' => ['sometimes', 'array'],
            'questions' => ['sometimes', 'array'],
            'questions.*.stem' => ['required_with:questions', 'string', 'max:2000'],
            'questions.*.question_type' => ['sometimes', 'string', 'in:mcq,true_false,short_answer'],
            'questions.*.difficulty' => ['sometimes', 'string', 'in:easy,medium,hard'],
            'questions.*.options' => ['sometimes', 'array'],
            'questions.*.correct_answer' => ['sometimes', 'array'],
            'questions.*.explanation' => ['sometimes', 'nullable', 'string', 'max:3000'],
            'questions.*.sort_order' => ['sometimes', 'integer', 'min:1'],
            'questions.*.status' => ['sometimes', 'string', 'in:active,inactive'],
            'questions.*.meta' => ['sometimes', 'array'],
        ];
    }
}
