<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\DepartmentServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\DepartmentStoreRequest;
use Modules\Academic\Http\Requests\DepartmentUpdateRequest;
use Modules\Academic\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    public function index(AcademicIndexRequest $request, DepartmentServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => DepartmentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(DepartmentStoreRequest $request, DepartmentServiceInterface $service): JsonResponse
    {
        $department = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data' => new DepartmentResource($department),
        ], 201);
    }

    public function show(int $id, DepartmentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new DepartmentResource($service->find($id)),
        ]);
    }

    public function update(int $id, DepartmentUpdateRequest $request, DepartmentServiceInterface $service): JsonResponse
    {
        $department = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data' => new DepartmentResource($department),
        ]);
    }

    public function destroy(int $id, DepartmentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.',
        ]);
    }
}
