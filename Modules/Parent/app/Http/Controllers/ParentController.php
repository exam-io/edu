<?php

namespace Modules\Parent\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Parent\Application\Contracts\ParentServiceInterface;
use Modules\Parent\Application\DTOs\ParentListQueryData;
use Modules\Parent\Application\DTOs\ParentMutationData;
use Modules\Parent\Http\Requests\ParentIndexRequest;
use Modules\Parent\Http\Requests\ParentStoreRequest;
use Modules\Parent\Http\Requests\ParentUpdateRequest;
use Modules\Parent\Http\Resources\ParentResource;

class ParentController extends Controller
{
    public function index(ParentIndexRequest $request, ParentServiceInterface $service): JsonResponse
    {
        $data = $service->list(ParentListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ParentResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ParentStoreRequest $request, ParentServiceInterface $service): JsonResponse
    {
        $parent = $service->create(ParentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Parent created successfully.',
            'data' => new ParentResource($parent),
        ], 201);
    }

    public function show(int $id, ParentServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ParentResource($service->find($id)),
        ]);
    }

    public function update(int $id, ParentUpdateRequest $request, ParentServiceInterface $service): JsonResponse
    {
        $parent = $service->update($id, ParentMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Parent updated successfully.',
            'data' => new ParentResource($parent),
        ]);
    }

    public function destroy(int $id, ParentServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Parent deleted successfully.',
        ]);
    }
}
