<?php

namespace Modules\Assessment\Application\Services;

use Illuminate\Support\Facades\Event;
use Modules\Assessment\Application\Contracts\AssessmentEvaluationServiceInterface;
use Modules\Assessment\Domain\Events\AssessmentEvaluated;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

class AssessmentEvaluationService implements AssessmentEvaluationServiceInterface
{
    public function evaluate(AssessmentAttempt $attempt): AssessmentAttempt
    {
        $attempt->load(['assessment.questions.question', 'answers']);

        $assessment = $attempt->assessment;
        $answersByQuestion = $attempt->answers->keyBy('question_id');

        $score = 0.0;

        foreach ($assessment->questions as $assessmentQuestion) {
            $answer = $answersByQuestion->get($assessmentQuestion->question_id);

            if (! $answer) {
                continue;
            }

            $questionType = $assessmentQuestion->question->question_type;

            if (! in_array($questionType, ['mcq', 'true_false'], true)) {
                $answer->is_correct = null;
                $answer->marks_awarded = 0;
                $answer->save();
                continue;
            }

            $isCorrect = $this->answersMatch(
                (array) ($assessmentQuestion->question->correct_answer ?? []),
                $this->extractSelectedAnswer((array) ($answer->selected_answer ?? [])),
            );

            $awarded = $isCorrect
                ? (float) $assessmentQuestion->marks
                : -1 * (float) $assessment->negative_marking;

            $answer->is_correct = $isCorrect;
            $answer->marks_awarded = $awarded;
            $answer->save();

            $score += $awarded;
        }

        $score = max(0, $score);
        $percentage = ((float) $assessment->total_marks) > 0
            ? round(($score / (float) $assessment->total_marks) * 100, 2)
            : 0.0;

        $attempt->score = $score;
        $attempt->percentage = $percentage;
        $attempt->status = 'evaluated';
        $attempt->save();

        Event::dispatch(new AssessmentEvaluated($attempt->id, $attempt->assessment_id, $attempt->tenant_id));

        return $attempt;
    }

    private function answersMatch(array $correct, array $selected): bool
    {
        sort($correct);
        sort($selected);

        return json_encode($correct) === json_encode($selected);
    }

    private function extractSelectedAnswer(array $payload): array
    {
        $answer = $payload['answer'] ?? $payload;

        return is_array($answer) ? $answer : [];
    }
}
