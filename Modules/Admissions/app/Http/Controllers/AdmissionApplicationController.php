<?php

namespace Modules\Admissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Admissions\Application\Contracts\AdmissionApplicationServiceInterface;
use Modules\Admissions\Application\DTOs\AdmissionApplicationData;
use Modules\Admissions\Application\DTOs\AdmissionApplicationQueryData;
use Modules\Admissions\Application\DTOs\AdmissionStatusTransitionData;
use Modules\Admissions\Http\Requests\AdmissionApplicationIndexRequest;
use Modules\Admissions\Http\Requests\AdmissionApplicationStatusUpdateRequest;
use Modules\Admissions\Http\Requests\AdmissionApplicationStoreRequest;
use Modules\Admissions\Http\Resources\AdmissionApplicationResource;

class AdmissionApplicationController extends Controller
{
    public function index(AdmissionApplicationIndexRequest $request, AdmissionApplicationServiceInterface $service): JsonResponse
    {
        $data = $service->list(AdmissionApplicationQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => AdmissionApplicationResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(AdmissionApplicationStoreRequest $request, AdmissionApplicationServiceInterface $service): JsonResponse
    {
        $application = $service->create(AdmissionApplicationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Admission application created successfully.',
            'data' => new AdmissionApplicationResource($application),
        ], 201);
    }

    public function show(int $id, AdmissionApplicationServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AdmissionApplicationResource($service->find($id)),
        ]);
    }

    public function changeStatus(int $id, AdmissionApplicationStatusUpdateRequest $request, AdmissionApplicationServiceInterface $service): JsonResponse
    {
        $application = $service->changeStatus($id, AdmissionStatusTransitionData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Admission application status updated successfully.',
            'data' => new AdmissionApplicationResource($application),
        ]);
    }

    public function destroy(int $id, AdmissionApplicationServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Admission application deleted successfully.',
        ]);
    }
}
