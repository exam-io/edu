<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\BatchServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\BatchStoreRequest;
use Modules\Academic\Http\Requests\BatchUpdateRequest;
use Modules\Academic\Http\Resources\BatchResource;

class BatchController extends Controller
{
    public function index(AcademicIndexRequest $request, BatchServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => BatchResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(BatchStoreRequest $request, BatchServiceInterface $service): JsonResponse
    {
        $batch = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Batch created successfully.',
            'data' => new BatchResource($batch),
        ], 201);
    }

    public function show(int $id, BatchServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new BatchResource($service->find($id)),
        ]);
    }

    public function update(int $id, BatchUpdateRequest $request, BatchServiceInterface $service): JsonResponse
    {
        $batch = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Batch updated successfully.',
            'data' => new BatchResource($batch),
        ]);
    }

    public function destroy(int $id, BatchServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Batch deleted successfully.',
        ]);
    }
}
