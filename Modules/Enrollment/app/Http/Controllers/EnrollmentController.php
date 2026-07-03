<?php

namespace Modules\Enrollment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Enrollment\Application\Contracts\EnrollmentServiceInterface;
use Modules\Enrollment\Application\DTOs\EnrollmentListQueryData;
use Modules\Enrollment\Application\DTOs\EnrollmentMutationData;
use Modules\Enrollment\Http\Requests\EnrollmentIndexRequest;
use Modules\Enrollment\Http\Requests\EnrollmentStoreRequest;
use Modules\Enrollment\Http\Requests\EnrollmentUpdateRequest;
use Modules\Enrollment\Http\Resources\EnrollmentResource;

class EnrollmentController extends Controller
{
    public function index(EnrollmentIndexRequest $request, EnrollmentServiceInterface $service): JsonResponse
    {
        $data = $service->list(EnrollmentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => EnrollmentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(EnrollmentStoreRequest $request, EnrollmentServiceInterface $service): JsonResponse
    {
        $enrollment = $service->create(EnrollmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Enrollment created successfully.',
            'data' => new EnrollmentResource($enrollment),
        ], 201);
    }

    public function show(int $id, EnrollmentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new EnrollmentResource($service->find($id)),
        ]);
    }

    public function update(int $id, EnrollmentUpdateRequest $request, EnrollmentServiceInterface $service): JsonResponse
    {
        $enrollment = $service->update($id, EnrollmentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Enrollment updated successfully.',
            'data' => new EnrollmentResource($enrollment),
        ]);
    }

    public function destroy(int $id, EnrollmentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment deleted successfully.',
        ]);
    }
}
