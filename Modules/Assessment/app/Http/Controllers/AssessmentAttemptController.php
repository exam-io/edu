<?php

namespace Modules\Assessment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Assessment\Application\Contracts\AssessmentAttemptServiceInterface;
use Modules\Assessment\Application\DTOs\AttemptAnswerData;
use Modules\Assessment\Http\Requests\AssessmentManualEvaluateRequest;
use Modules\Assessment\Http\Requests\AssessmentSaveAnswerRequest;
use Modules\Assessment\Http\Requests\AssessmentStartRequest;
use Modules\Assessment\Http\Requests\AssessmentSubmitRequest;
use Modules\Assessment\Http\Resources\AssessmentAttemptResource;
use Modules\Assessment\Http\Resources\AssessmentResultResource;

class AssessmentAttemptController extends Controller
{
    public function start(int $id, AssessmentStartRequest $request, AssessmentAttemptServiceInterface $service): JsonResponse
    {
        $attempt = $service->start($id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment attempt started.',
            'data' => new AssessmentAttemptResource($attempt),
        ]);
    }

    public function saveAnswer(int $id, AssessmentSaveAnswerRequest $request, AssessmentAttemptServiceInterface $service): JsonResponse
    {
        $attempt = $service->saveAnswer($id, AttemptAnswerData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Answer saved.',
            'data' => new AssessmentAttemptResource($attempt),
        ]);
    }

    public function submit(int $id, AssessmentSubmitRequest $request, AssessmentAttemptServiceInterface $service): JsonResponse
    {
        $attempt = $service->submit($id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment submitted.',
            'data' => new AssessmentAttemptResource($attempt),
        ]);
    }

    public function evaluate(int $id, int $attemptId, AssessmentManualEvaluateRequest $request, AssessmentAttemptServiceInterface $service): JsonResponse
    {
        $attempt = $service->evaluateAttempt($id, $attemptId, (array) $request->validated('answers'));

        return response()->json([
            'success' => true,
            'message' => 'Attempt evaluated successfully.',
            'data' => new AssessmentAttemptResource($attempt),
        ]);
    }

    public function result(int $id, AssessmentAttemptServiceInterface $service): JsonResponse
    {
        $result = $service->result($id);

        return response()->json([
            'success' => true,
            'data' => new AssessmentResultResource($result),
        ]);
    }
}
