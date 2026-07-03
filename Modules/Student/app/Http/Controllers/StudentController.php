<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Student\Application\Contracts\StudentServiceInterface;
use Modules\Student\Application\DTOs\StudentListQueryData;
use Modules\Student\Application\DTOs\StudentMutationData;
use Modules\Student\Http\Requests\StudentIndexRequest;
use Modules\Student\Http\Requests\StudentStoreRequest;
use Modules\Student\Http\Requests\StudentUpdateRequest;
use Modules\Student\Http\Resources\StudentResource;

class StudentController extends Controller
{
    public function index(StudentIndexRequest $request, StudentServiceInterface $service): JsonResponse
    {
        $data = $service->list(StudentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => StudentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(StudentStoreRequest $request, StudentServiceInterface $service): JsonResponse
    {
        $student = $service->create(StudentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
            'data' => new StudentResource($student),
        ], 201);
    }

    public function show(int $id, StudentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new StudentResource($service->find($id)),
        ]);
    }

    public function update(int $id, StudentUpdateRequest $request, StudentServiceInterface $service): JsonResponse
    {
        $student = $service->update($id, StudentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'data' => new StudentResource($student),
        ]);
    }

    public function destroy(int $id, StudentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.',
        ]);
    }
}
