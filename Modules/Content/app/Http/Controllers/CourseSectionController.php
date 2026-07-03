<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Content\Application\Contracts\CourseSectionServiceInterface;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Http\Requests\ContentIndexRequest;
use Modules\Content\Http\Requests\CourseSectionStoreRequest;
use Modules\Content\Http\Requests\CourseSectionUpdateRequest;
use Modules\Content\Http\Resources\CourseSectionResource;

class CourseSectionController extends Controller
{
    public function index(ContentIndexRequest $request, CourseSectionServiceInterface $service): JsonResponse
    {
        $data = $service->list(ContentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CourseSectionResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(CourseSectionStoreRequest $request, CourseSectionServiceInterface $service): JsonResponse
    {
        $section = $service->create(ContentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully.',
            'data' => new CourseSectionResource($section),
        ], 201);
    }

    public function show(int $id, CourseSectionServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CourseSectionResource($service->find($id)),
        ]);
    }

    public function update(int $id, CourseSectionUpdateRequest $request, CourseSectionServiceInterface $service): JsonResponse
    {
        $section = $service->update($id, ContentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully.',
            'data' => new CourseSectionResource($section),
        ]);
    }

    public function destroy(int $id, CourseSectionServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully.',
        ]);
    }
}
