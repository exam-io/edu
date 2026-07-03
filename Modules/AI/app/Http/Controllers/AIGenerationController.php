<?php

namespace Modules\AI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\AI\Application\Contracts\AIGenerationServiceInterface;
use Modules\AI\Application\DTOs\AIGenerationRequestData;
use Modules\AI\Application\DTOs\AIRequestListQueryData;
use Modules\AI\Http\Requests\AIGenerationIndexRequest;
use Modules\AI\Http\Requests\AIGenerationStoreRequest;
use Modules\AI\Http\Resources\AIGenerationRequestResource;

class AIGenerationController extends Controller
{
    public function index(AIGenerationIndexRequest $request, AIGenerationServiceInterface $service): JsonResponse
    {
        $data = $service->list(AIRequestListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => AIGenerationRequestResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(AIGenerationStoreRequest $request, AIGenerationServiceInterface $service): JsonResponse
    {
        $generatedRequest = $service->create(AIGenerationRequestData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'AI generation request queued successfully.',
            'data' => new AIGenerationRequestResource($generatedRequest),
        ], 201);
    }

    public function show(int $id, AIGenerationServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AIGenerationRequestResource($service->find($id)),
        ]);
    }

    public function destroy(int $id, AIGenerationServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'AI generation request deleted successfully.',
        ]);
    }
}
