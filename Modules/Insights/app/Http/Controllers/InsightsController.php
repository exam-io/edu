<?php

namespace Modules\Insights\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Insights\Application\Contracts\InsightsServiceInterface;
use Modules\Insights\Application\DTOs\InsightQueryData;
use Modules\Insights\Http\Requests\InsightIndexRequest;
use Modules\Insights\Http\Resources\GeneratedInsightResource;

class InsightsController extends Controller
{
    public function index(InsightIndexRequest $request, InsightsServiceInterface $service): JsonResponse
    {
        $data = $service->listGenerated(InsightQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => GeneratedInsightResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function generate(InsightsServiceInterface $service): JsonResponse
    {
        $count = $service->generateNow();

        return response()->json([
            'success' => true,
            'message' => 'Insight generation triggered successfully.',
            'data' => [
                'generated_count' => $count,
            ],
        ]);
    }
}
