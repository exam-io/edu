<?php

namespace Modules\Campaign\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Campaign\Application\Contracts\CampaignServiceInterface;
use Modules\Campaign\Application\DTOs\CampaignMutationData;
use Modules\Campaign\Application\DTOs\CampaignQueryData;
use Modules\Campaign\Http\Requests\CampaignIndexRequest;
use Modules\Campaign\Http\Requests\CampaignLaunchRequest;
use Modules\Campaign\Http\Requests\CampaignStoreRequest;
use Modules\Campaign\Http\Requests\CampaignUpdateRequest;
use Modules\Campaign\Http\Resources\CampaignResource;

class CampaignController extends Controller
{
    public function index(CampaignIndexRequest $request, CampaignServiceInterface $service): JsonResponse
    {
        $data = $service->list(CampaignQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CampaignResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(CampaignStoreRequest $request, CampaignServiceInterface $service): JsonResponse
    {
        $campaign = $service->create(CampaignMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Campaign created successfully.',
            'data' => new CampaignResource($campaign),
        ], 201);
    }

    public function show(int $id, CampaignServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CampaignResource($service->find($id)),
        ]);
    }

    public function update(int $id, CampaignUpdateRequest $request, CampaignServiceInterface $service): JsonResponse
    {
        $campaign = $service->update($id, CampaignMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Campaign updated successfully.',
            'data' => new CampaignResource($campaign),
        ]);
    }

    public function launch(int $id, CampaignLaunchRequest $request, CampaignServiceInterface $service): JsonResponse
    {
        $campaign = $service->launch($id);

        return response()->json([
            'success' => true,
            'message' => 'Campaign launch requested successfully.',
            'data' => new CampaignResource($campaign),
        ]);
    }

    public function destroy(int $id, CampaignServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Campaign deleted successfully.',
        ]);
    }
}
