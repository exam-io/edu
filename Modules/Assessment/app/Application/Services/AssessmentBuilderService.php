<?php

namespace Modules\Assessment\Application\Services;

use Modules\Assessment\Application\Contracts\AssessmentBuilderServiceInterface;
use Modules\Assessment\Domain\Models\Assessment;

class AssessmentBuilderService implements AssessmentBuilderServiceInterface
{
    public function syncQuestions(Assessment $assessment, array $questions): Assessment
    {
        $assessment->questions()->delete();

        foreach ($questions as $index => $question) {
            $assessment->questions()->create([
                'tenant_id' => $assessment->tenant_id,
                'question_id' => (int) ($question['question_id'] ?? 0),
                'marks' => (float) ($question['marks'] ?? 1),
                'sort_order' => (int) ($question['sort_order'] ?? ($index + 1)),
            ]);
        }

        return $assessment->refresh()->load('questions.question');
    }
}
