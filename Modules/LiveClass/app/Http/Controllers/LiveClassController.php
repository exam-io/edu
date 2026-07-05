<?php

namespace Modules\LiveClass\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\LiveClass\Application\Contracts\LiveClassServiceInterface;
use Modules\LiveClass\Application\DTOs\LiveClassMutationData;
use Modules\LiveClass\Application\DTOs\LiveClassQueryData;
use Modules\LiveClass\Http\Requests\LiveClassIndexRequest;
use Modules\LiveClass\Http\Requests\LiveClassStoreRequest;
use Modules\LiveClass\Http\Requests\LiveClassUpdateRequest;
use Modules\LiveClass\Http\Resources\LiveClassAttendanceResource;
use Modules\LiveClass\Http\Resources\LiveClassSessionResource;

class LiveClassController extends Controller
{
    public function index(LiveClassIndexRequest $request, LiveClassServiceInterface $service): JsonResponse
    {
        $data = $service->list(LiveClassQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => LiveClassSessionResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(LiveClassStoreRequest $request, LiveClassServiceInterface $service): JsonResponse
    {
        $session = $service->create(LiveClassMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Live class created successfully.',
            'data' => new LiveClassSessionResource($session),
        ], 201);
    }

    public function show(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new LiveClassSessionResource($service->find($id)),
        ]);
    }

    public function update(int $id, LiveClassUpdateRequest $request, LiveClassServiceInterface $service): JsonResponse
    {
        $session = $service->update($id, LiveClassMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Live class updated successfully.',
            'data' => new LiveClassSessionResource($session),
        ]);
    }

    public function destroy(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Live class deleted successfully.',
        ]);
    }

    public function start(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Live class started successfully.',
            'data' => new LiveClassSessionResource($service->start($id)),
        ]);
    }

    public function join(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Live class joined successfully.',
            'data' => new LiveClassSessionResource($service->join($id)),
        ]);
    }

    public function end(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Live class ended successfully.',
            'data' => new LiveClassSessionResource($service->end($id)),
        ]);
    }

    public function attendance(int $id, LiveClassServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => LiveClassAttendanceResource::collection($service->attendance($id)),
        ]);
    }
}
