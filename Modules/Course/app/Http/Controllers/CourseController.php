<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Course\Application\Contracts\CourseServiceInterface;
use Modules\Course\Application\DTOs\CourseListQueryData;
use Modules\Course\Application\DTOs\CourseMutationData;
use Modules\Course\Http\Requests\CourseIndexRequest;
use Modules\Course\Http\Requests\CourseStoreRequest;
use Modules\Course\Http\Requests\CourseUpdateRequest;
use Modules\Course\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index(CourseIndexRequest $request, CourseServiceInterface $service): JsonResponse
    {
        $data = $service->list(CourseListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CourseResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(CourseStoreRequest $request, CourseServiceInterface $service): JsonResponse
    {
        $course = $service->create(CourseMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully.',
            'data' => new CourseResource($course),
        ], 201);
    }

    public function show(int $id, CourseServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CourseResource($service->find($id)),
        ]);
    }

    public function update(int $id, CourseUpdateRequest $request, CourseServiceInterface $service): JsonResponse
    {
        $course = $service->update($id, CourseMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully.',
            'data' => new CourseResource($course),
        ]);
    }

    public function destroy(int $id, CourseServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }
}
