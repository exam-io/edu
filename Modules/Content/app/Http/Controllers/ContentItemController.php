<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Content\Application\Contracts\ContentItemServiceInterface;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Http\Requests\ContentIndexRequest;
use Modules\Content\Http\Requests\ContentItemStoreRequest;
use Modules\Content\Http\Requests\ContentItemUpdateRequest;
use Modules\Content\Http\Resources\ContentItemResource;

class ContentItemController extends Controller
{
    public function index(ContentIndexRequest $request, ContentItemServiceInterface $service): JsonResponse
    {
        $data = $service->list(ContentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ContentItemResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ContentItemStoreRequest $request, ContentItemServiceInterface $service): JsonResponse
    {
        $item = $service->create(ContentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Content item created successfully.',
            'data' => new ContentItemResource($item),
        ], 201);
    }

    public function show(int $id, ContentItemServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ContentItemResource($service->find($id)),
        ]);
    }

    public function update(int $id, ContentItemUpdateRequest $request, ContentItemServiceInterface $service): JsonResponse
    {
        $item = $service->update($id, ContentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Content item updated successfully.',
            'data' => new ContentItemResource($item),
        ]);
    }

    public function destroy(int $id, ContentItemServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Content item deleted successfully.',
        ]);
    }
}
