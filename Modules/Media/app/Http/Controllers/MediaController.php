<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Media\Application\Contracts\MediaServiceInterface;
use Modules\Media\Application\DTOs\MediaListQueryData;
use Modules\Media\Application\DTOs\MediaMutationData;
use Modules\Media\Http\Requests\MediaIndexRequest;
use Modules\Media\Http\Requests\MediaStoreRequest;
use Modules\Media\Http\Requests\MediaUpdateRequest;
use Modules\Media\Http\Resources\MediaAssetResource;

class MediaController extends Controller
{
    public function index(MediaIndexRequest $request, MediaServiceInterface $service): JsonResponse
    {
        $data = $service->list(MediaListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => MediaAssetResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(MediaStoreRequest $request, MediaServiceInterface $service): JsonResponse
    {
        $asset = $service->create(MediaMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'data' => new MediaAssetResource($asset),
        ], 201);
    }

    public function show(int $id, MediaServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new MediaAssetResource($service->find($id)),
        ]);
    }

    public function update(int $id, MediaUpdateRequest $request, MediaServiceInterface $service): JsonResponse
    {
        $asset = $service->update($id, MediaMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully.',
            'data' => new MediaAssetResource($asset),
        ]);
    }

    public function destroy(int $id, MediaServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }
}
