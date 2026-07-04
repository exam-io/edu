<?php

namespace Modules\Assignment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionServiceInterface;
use Modules\Assignment\Application\DTOs\AssignmentSubmissionData;
use Modules\Assignment\Http\Requests\AssignmentSubmissionIndexRequest;
use Modules\Assignment\Http\Requests\AssignmentSubmitRequest;
use Modules\Assignment\Http\Resources\AssignmentSubmissionResource;

class AssignmentSubmissionController extends Controller
{
    public function submit(int $id, AssignmentSubmitRequest $request, AssignmentSubmissionServiceInterface $service): JsonResponse
    {
        $payload = $request->validated();
        $payload['assessment_id'] = $id;

        $submission = $service->submit(AssignmentSubmissionData::fromArray($payload));

        return response()->json([
            'success' => true,
            'message' => 'Assignment submitted successfully.',
            'data' => new AssignmentSubmissionResource($submission),
        ], 201);
    }

    public function index(AssignmentSubmissionIndexRequest $request, AssignmentSubmissionServiceInterface $service): JsonResponse
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);
        unset($validated['per_page']);

        $data = $service->list($validated, $perPage);

        return response()->json([
            'success' => true,
            'data' => AssignmentSubmissionResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }
}
