<?php

namespace Modules\QuestionBank\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_set_id' => $this->question_set_id,
            'stem' => $this->stem,
            'question_type' => $this->question_type,
            'difficulty' => $this->difficulty,
            'options' => $this->options,
            'correct_answer' => $this->correct_answer,
            'explanation' => $this->explanation,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'meta' => $this->meta,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
