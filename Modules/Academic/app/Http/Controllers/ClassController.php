<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\ClassServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\ClassStoreRequest;
use Modules\Academic\Http\Requests\ClassUpdateRequest;
use Modules\Academic\Http\Resources\ClassResource;

class ClassController extends Controller
{
    public function index(AcademicIndexRequest $request, ClassServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ClassResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ClassStoreRequest $request, ClassServiceInterface $service): JsonResponse
    {
        $class = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Class created successfully.',
            'data' => new ClassResource($class),
        ], 201);
    }

    public function show(int $id, ClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ClassResource($service->find($id)),
        ]);
    }

    public function update(int $id, ClassUpdateRequest $request, ClassServiceInterface $service): JsonResponse
    {
        $class = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Class updated successfully.',
            'data' => new ClassResource($class),
        ]);
    }

    public function destroy(int $id, ClassServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Class deleted successfully.',
        ]);
    }
}
