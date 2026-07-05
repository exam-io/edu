<?php

namespace Modules\Reporting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Reporting\Application\Contracts\ReportingServiceInterface;
use Modules\Reporting\Application\DTOs\ReportQueryData;
use Modules\Reporting\Application\DTOs\ReportTemplateData;
use Modules\Reporting\Application\DTOs\ScheduleReportData;
use Modules\Reporting\Http\Requests\ReportIndexRequest;
use Modules\Reporting\Http\Requests\ReportScheduleRequest;
use Modules\Reporting\Http\Requests\ReportStoreRequest;
use Modules\Reporting\Http\Requests\RunReportRequest;
use Modules\Reporting\Http\Resources\ReportExecutionResource;
use Modules\Reporting\Http\Resources\ReportScheduleResource;
use Modules\Reporting\Http\Resources\ReportTemplateResource;

class ReportingController extends Controller
{
    public function index(ReportIndexRequest $request, ReportingServiceInterface $service): JsonResponse
    {
        $data = $service->listTemplates(ReportQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ReportTemplateResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ReportStoreRequest $request, ReportingServiceInterface $service): JsonResponse
    {
        $template = $service->createTemplate(ReportTemplateData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Report template created successfully.',
            'data' => new ReportTemplateResource($template),
        ], 201);
    }

    public function run(int $id, RunReportRequest $request, ReportingServiceInterface $service): JsonResponse
    {
        $payload = $request->validated();
        $execution = $service->requestRun($id, is_array($payload['filters'] ?? null) ? $payload['filters'] : []);

        return response()->json([
            'success' => true,
            'message' => 'Report queued for generation.',
            'data' => new ReportExecutionResource($execution),
        ], 202);
    }

    public function schedule(ReportScheduleRequest $request, ReportingServiceInterface $service): JsonResponse
    {
        $schedule = $service->schedule(ScheduleReportData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Report schedule created successfully.',
            'data' => new ReportScheduleResource($schedule),
        ], 201);
    }
}
