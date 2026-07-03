<?php

namespace Modules\Shared\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Application\Contracts\SharedLookupServiceInterface;
use Modules\Shared\Http\Requests\SharedLookupIndexRequest;
use Modules\Shared\Http\Requests\SharedLookupShowRequest;

class SharedController extends Controller
{
    public function __construct(
        private readonly SharedLookupServiceInterface $lookupService,
    ) {
    }

    public function index(SharedLookupIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return response()->json([
            'success' => true,
            'data' => $this->lookupService->list(
                type: (string) $validated['type'],
                search: isset($validated['search']) ? (string) $validated['search'] : null,
                limit: isset($validated['limit']) ? (int) $validated['limit'] : 25,
            ),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'supported_types' => $this->lookupService->supportedTypes(),
                'query_params' => ['type', 'search', 'limit'],
            ],
        ]);
    }

    public function store(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Shared lookup catalog is read-only.',
        ], 405);
    }

    public function show(string $id, SharedLookupShowRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return response()->json([
            'success' => true,
            'data' => $this->lookupService->find(
                type: (string) $validated['type'],
                id: $id,
            ),
        ]);
    }

    public function edit(string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'resource_id' => $id,
                'supported_types' => $this->lookupService->supportedTypes(),
            ],
        ]);
    }

    public function update(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Shared lookup catalog is read-only.',
            'resource_id' => $id,
        ], 405);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Shared lookup catalog is read-only.',
            'resource_id' => $id,
        ], 405);
    }
}
