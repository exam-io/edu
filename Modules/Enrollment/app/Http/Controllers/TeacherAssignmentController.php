<?php

namespace Modules\Enrollment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Enrollment\Application\Contracts\TeacherAssignmentServiceInterface;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentListQueryData;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentMutationData;
use Modules\Enrollment\Http\Requests\TeacherAssignmentIndexRequest;
use Modules\Enrollment\Http\Requests\TeacherAssignmentStoreRequest;
use Modules\Enrollment\Http\Requests\TeacherAssignmentUpdateRequest;
use Modules\Enrollment\Http\Resources\TeacherAssignmentResource;

class TeacherAssignmentController extends Controller
{
    public function index(TeacherAssignmentIndexRequest $request, TeacherAssignmentServiceInterface $service): JsonResponse
    {
        $data = $service->list(TeacherAssignmentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => TeacherAssignmentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(TeacherAssignmentStoreRequest $request, TeacherAssignmentServiceInterface $service): JsonResponse
    {
        $assignment = $service->create(TeacherAssignmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Teacher assignment created successfully.',
            'data' => new TeacherAssignmentResource($assignment),
        ], 201);
    }

    public function show(int $id, TeacherAssignmentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new TeacherAssignmentResource($service->find($id)),
        ]);
    }

    public function update(int $id, TeacherAssignmentUpdateRequest $request, TeacherAssignmentServiceInterface $service): JsonResponse
    {
        $assignment = $service->update($id, TeacherAssignmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Teacher assignment updated successfully.',
            'data' => new TeacherAssignmentResource($assignment),
        ]);
    }

    public function destroy(int $id, TeacherAssignmentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Teacher assignment deleted successfully.',
        ]);
    }
}
