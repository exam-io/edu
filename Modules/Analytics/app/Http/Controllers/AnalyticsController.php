<?php

namespace Modules\Analytics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Analytics\Application\Contracts\AnalyticsServiceInterface;
use Modules\Analytics\Application\DTOs\AnalyticsQueryData;
use Modules\Analytics\Application\DTOs\TrackEventData;
use Modules\Analytics\Http\Requests\AnalyticsIndexRequest;
use Modules\Analytics\Http\Requests\AnalyticsTrackEventRequest;
use Modules\Analytics\Http\Resources\AnalyticsEventResource;
use Modules\Analytics\Http\Resources\MetricSnapshotResource;

class AnalyticsController extends Controller
{
    public function index(AnalyticsIndexRequest $request, AnalyticsServiceInterface $service): JsonResponse
    {
        $data = $service->listEvents(AnalyticsQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => AnalyticsEventResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function metrics(AnalyticsIndexRequest $request, AnalyticsServiceInterface $service): JsonResponse
    {
        $metricKey = (string) ($request->validated()['metric_key'] ?? 'events.count');
        $data = $service->metricSeries($metricKey);

        return response()->json([
            'success' => true,
            'data' => MetricSnapshotResource::collection($data),
        ]);
    }

    public function track(AnalyticsTrackEventRequest $request, AnalyticsServiceInterface $service): JsonResponse
    {
        $event = $service->track(TrackEventData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Analytics event tracked successfully.',
            'data' => new AnalyticsEventResource($event),
        ], 201);
    }
}
