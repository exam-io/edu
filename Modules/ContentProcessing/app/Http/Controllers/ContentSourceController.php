<?php

namespace Modules\ContentProcessing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\ContentProcessing\Application\Contracts\ContentProcessingServiceInterface;
use Modules\ContentProcessing\Application\DTOs\ContentSourceListQueryData;
use Modules\ContentProcessing\Application\DTOs\ContentSourceMutationData;
use Modules\ContentProcessing\Http\Requests\ContentSourceIndexRequest;
use Modules\ContentProcessing\Http\Requests\ContentSourceStoreRequest;
use Modules\ContentProcessing\Http\Resources\ContentSourceResource;

class ContentSourceController extends Controller
{
    public function index(ContentSourceIndexRequest $request, ContentProcessingServiceInterface $service): JsonResponse
    {
        $data = $service->list(ContentSourceListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ContentSourceResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ContentSourceStoreRequest $request, ContentProcessingServiceInterface $service): JsonResponse
    {
        $source = $service->create(ContentSourceMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Content source uploaded and queued for processing.',
            'data' => new ContentSourceResource($source),
        ], 201);
    }

    public function show(int $id, ContentProcessingServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ContentSourceResource($service->find($id)),
        ]);
    }

    public function retry(int $id, ContentProcessingServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Content extraction re-queued successfully.',
            'data' => new ContentSourceResource($service->retry($id)),
        ]);
    }

    public function destroy(int $id, ContentProcessingServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Content source deleted successfully.',
        ]);
    }
}
