<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\CRM\Application\Contracts\LeadServiceInterface;
use Modules\CRM\Application\DTOs\LeadMutationData;
use Modules\CRM\Application\DTOs\LeadQueryData;
use Modules\CRM\Http\Requests\LeadIndexRequest;
use Modules\CRM\Http\Requests\LeadStoreRequest;
use Modules\CRM\Http\Requests\LeadUpdateRequest;
use Modules\CRM\Http\Resources\LeadResource;

class LeadController extends Controller
{
    public function index(LeadIndexRequest $request, LeadServiceInterface $service): JsonResponse
    {
        $data = $service->list(LeadQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => LeadResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(LeadStoreRequest $request, LeadServiceInterface $service): JsonResponse
    {
        $lead = $service->create(LeadMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully.',
            'data' => new LeadResource($lead),
        ], 201);
    }

    public function show(int $id, LeadServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new LeadResource($service->find($id)),
        ]);
    }

    public function update(int $id, LeadUpdateRequest $request, LeadServiceInterface $service): JsonResponse
    {
        $lead = $service->update($id, LeadMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully.',
            'data' => new LeadResource($lead),
        ]);
    }

    public function destroy(int $id, LeadServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully.',
        ]);
    }
}
