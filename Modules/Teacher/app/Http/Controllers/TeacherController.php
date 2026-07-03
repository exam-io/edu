<?php

namespace Modules\Teacher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Teacher\Application\Contracts\TeacherServiceInterface;
use Modules\Teacher\Application\DTOs\TeacherListQueryData;
use Modules\Teacher\Application\DTOs\TeacherMutationData;
use Modules\Teacher\Http\Requests\TeacherIndexRequest;
use Modules\Teacher\Http\Requests\TeacherStoreRequest;
use Modules\Teacher\Http\Requests\TeacherUpdateRequest;
use Modules\Teacher\Http\Resources\TeacherResource;

class TeacherController extends Controller
{
    public function index(TeacherIndexRequest $request, TeacherServiceInterface $service): JsonResponse
    {
        $data = $service->list(TeacherListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => TeacherResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(TeacherStoreRequest $request, TeacherServiceInterface $service): JsonResponse
    {
        $teacher = $service->create(TeacherMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully.',
            'data' => new TeacherResource($teacher),
        ], 201);
    }

    public function show(int $id, TeacherServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new TeacherResource($service->find($id)),
        ]);
    }

    public function update(int $id, TeacherUpdateRequest $request, TeacherServiceInterface $service): JsonResponse
    {
        $teacher = $service->update($id, TeacherMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'data' => new TeacherResource($teacher),
        ]);
    }

    public function destroy(int $id, TeacherServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully.',
        ]);
    }
}
