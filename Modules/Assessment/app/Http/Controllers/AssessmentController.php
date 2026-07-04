<?php

namespace Modules\Assessment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Assessment\Application\Contracts\AssessmentServiceInterface;
use Modules\Assessment\Application\DTOs\AssessmentMutationData;
use Modules\Assessment\Application\DTOs\AssessmentQueryData;
use Modules\Assessment\Http\Requests\AssessmentIndexRequest;
use Modules\Assessment\Http\Requests\AssessmentPublishRequest;
use Modules\Assessment\Http\Requests\AssessmentStoreRequest;
use Modules\Assessment\Http\Requests\AssessmentUpdateRequest;
use Modules\Assessment\Http\Resources\AssessmentResource;

class AssessmentController extends Controller
{
    public function index(AssessmentIndexRequest $request, AssessmentServiceInterface $service): JsonResponse
    {
        $data = $service->list(AssessmentQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => AssessmentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(AssessmentStoreRequest $request, AssessmentServiceInterface $service): JsonResponse
    {
        $assessment = $service->create(AssessmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Assessment created successfully.',
            'data' => new AssessmentResource($assessment),
        ], 201);
    }

    public function show(int $id, AssessmentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AssessmentResource($service->find($id)),
        ]);
    }

    public function update(int $id, AssessmentUpdateRequest $request, AssessmentServiceInterface $service): JsonResponse
    {
        $assessment = $service->update($id, AssessmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Assessment updated successfully.',
            'data' => new AssessmentResource($assessment),
        ]);
    }

    public function destroy(int $id, AssessmentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment deleted successfully.',
        ]);
    }

    public function publish(int $id, AssessmentPublishRequest $request, AssessmentServiceInterface $service): JsonResponse
    {
        $assessment = $service->publish($id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment published successfully.',
            'data' => new AssessmentResource($assessment),
        ]);
    }
}
