<?php

namespace Modules\LMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\LMS\Application\Contracts\LearningProgressServiceInterface;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Http\Requests\LearningProgressStoreRequest;
use Modules\LMS\Http\Requests\LearningProgressUpdateRequest;
use Modules\LMS\Http\Requests\LmsIndexRequest;
use Modules\LMS\Http\Resources\LearningProgressResource;

class LearningProgressController extends Controller
{
    public function index(LmsIndexRequest $request, LearningProgressServiceInterface $service): JsonResponse
    {
        $data = $service->list(LmsListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => LearningProgressResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(LearningProgressStoreRequest $request, LearningProgressServiceInterface $service): JsonResponse
    {
        $progress = $service->create(LmsMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Progress created successfully.',
            'data' => new LearningProgressResource($progress),
        ], 201);
    }

    public function show(int $id, LearningProgressServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new LearningProgressResource($service->find($id)),
        ]);
    }

    public function update(int $id, LearningProgressUpdateRequest $request, LearningProgressServiceInterface $service): JsonResponse
    {
        $progress = $service->update($id, LmsMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully.',
            'data' => new LearningProgressResource($progress),
        ]);
    }

    public function destroy(int $id, LearningProgressServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Progress deleted successfully.',
        ]);
    }
}
