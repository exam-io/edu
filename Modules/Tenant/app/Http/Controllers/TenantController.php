<?php

namespace Modules\Tenant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'module' => 'tenant',
            'status' => 'ready',
            'phase' => 'foundation',
        ]);
    }

    public function create(): JsonResponse
    {
        return $this->notImplemented();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->notImplemented();
    }

    public function show(string $id): JsonResponse
    {
        return $this->notImplemented(['resource_id' => $id]);
    }

    public function edit(string $id): JsonResponse
    {
        return $this->notImplemented(['resource_id' => $id]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return $this->notImplemented(['resource_id' => $id]);
    }

    public function destroy(string $id): JsonResponse
    {
        return $this->notImplemented(['resource_id' => $id]);
    }

    private function notImplemented(array $extra = []): JsonResponse
    {
        return response()->json([
            'module' => 'tenant',
            'message' => 'Endpoint is intentionally not implemented in Step 1 foundation.',
            ...$extra,
        ], 501);
    }
}
