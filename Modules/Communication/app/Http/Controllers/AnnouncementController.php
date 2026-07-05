<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Communication\Application\Contracts\CommunicationServiceInterface;
use Modules\Communication\Application\DTOs\AnnouncementData;
use Modules\Communication\Application\DTOs\CommunicationHistoryQueryData;
use Modules\Communication\Http\Requests\AnnouncementIndexRequest;
use Modules\Communication\Http\Requests\AnnouncementPublishRequest;
use Modules\Communication\Http\Requests\AnnouncementStoreRequest;
use Modules\Communication\Http\Resources\AnnouncementResource;

class AnnouncementController extends Controller
{
    public function index(AnnouncementIndexRequest $request, CommunicationServiceInterface $service): JsonResponse
    {
        $data = $service->listAnnouncements(CommunicationHistoryQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => AnnouncementResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(AnnouncementStoreRequest $request, CommunicationServiceInterface $service): JsonResponse
    {
        $announcement = $service->createAnnouncement(AnnouncementData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully.',
            'data' => new AnnouncementResource($announcement),
        ], 201);
    }

    public function publish(int $id, AnnouncementPublishRequest $request, CommunicationServiceInterface $service): JsonResponse
    {
        $announcement = $service->publishAnnouncement($id);

        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully.',
            'data' => new AnnouncementResource($announcement),
        ]);
    }
}
