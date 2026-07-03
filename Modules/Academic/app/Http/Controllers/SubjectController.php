<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\SubjectServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\SubjectStoreRequest;
use Modules\Academic\Http\Requests\SubjectUpdateRequest;
use Modules\Academic\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    public function index(AcademicIndexRequest $request, SubjectServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => SubjectResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(SubjectStoreRequest $request, SubjectServiceInterface $service): JsonResponse
    {
        $subject = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully.',
            'data' => new SubjectResource($subject),
        ], 201);
    }

    public function show(int $id, SubjectServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new SubjectResource($service->find($id)),
        ]);
    }

    public function update(int $id, SubjectUpdateRequest $request, SubjectServiceInterface $service): JsonResponse
    {
        $subject = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully.',
            'data' => new SubjectResource($subject),
        ]);
    }

    public function destroy(int $id, SubjectServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully.',
        ]);
    }
}
