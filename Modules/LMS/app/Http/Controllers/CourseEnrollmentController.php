<?php

namespace Modules\LMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\LMS\Application\Contracts\CourseEnrollmentServiceInterface;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Http\Requests\CourseEnrollmentStoreRequest;
use Modules\LMS\Http\Requests\CourseEnrollmentUpdateRequest;
use Modules\LMS\Http\Requests\LmsIndexRequest;
use Modules\LMS\Http\Resources\CourseEnrollmentResource;

class CourseEnrollmentController extends Controller
{
    public function index(LmsIndexRequest $request, CourseEnrollmentServiceInterface $service): JsonResponse
    {
        $data = $service->list(LmsListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CourseEnrollmentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(CourseEnrollmentStoreRequest $request, CourseEnrollmentServiceInterface $service): JsonResponse
    {
        $enrollment = $service->create(LmsMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Enrollment created successfully.',
            'data' => new CourseEnrollmentResource($enrollment),
        ], 201);
    }

    public function show(int $id, CourseEnrollmentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CourseEnrollmentResource($service->find($id)),
        ]);
    }

    public function update(int $id, CourseEnrollmentUpdateRequest $request, CourseEnrollmentServiceInterface $service): JsonResponse
    {
        $enrollment = $service->update($id, LmsMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Enrollment updated successfully.',
            'data' => new CourseEnrollmentResource($enrollment),
        ]);
    }

    public function destroy(int $id, CourseEnrollmentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment deleted successfully.',
        ]);
    }
}
