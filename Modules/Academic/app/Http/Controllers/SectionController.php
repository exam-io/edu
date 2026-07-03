<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\SectionServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\SectionStoreRequest;
use Modules\Academic\Http\Requests\SectionUpdateRequest;
use Modules\Academic\Http\Resources\SectionResource;

class SectionController extends Controller
{
    public function index(AcademicIndexRequest $request, SectionServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => SectionResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(SectionStoreRequest $request, SectionServiceInterface $service): JsonResponse
    {
        $section = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully.',
            'data' => new SectionResource($section),
        ], 201);
    }

    public function show(int $id, SectionServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new SectionResource($service->find($id)),
        ]);
    }

    public function update(int $id, SectionUpdateRequest $request, SectionServiceInterface $service): JsonResponse
    {
        $section = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully.',
            'data' => new SectionResource($section),
        ]);
    }

    public function destroy(int $id, SectionServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully.',
        ]);
    }
}
