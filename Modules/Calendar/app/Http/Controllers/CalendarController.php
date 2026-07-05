<?php

namespace Modules\Calendar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Calendar\Application\Contracts\CalendarEventServiceInterface;
use Modules\Calendar\Application\DTOs\CalendarEventMutationData;
use Modules\Calendar\Application\DTOs\CalendarEventQueryData;
use Modules\Calendar\Http\Requests\CalendarIndexRequest;
use Modules\Calendar\Http\Requests\CalendarStoreRequest;
use Modules\Calendar\Http\Requests\CalendarUpdateRequest;
use Modules\Calendar\Http\Resources\CalendarEventResource;

class CalendarController extends Controller
{
    public function index(CalendarIndexRequest $request, CalendarEventServiceInterface $service): JsonResponse
    {
        $data = $service->list(CalendarEventQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CalendarEventResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(CalendarStoreRequest $request, CalendarEventServiceInterface $service): JsonResponse
    {
        $event = $service->create(CalendarEventMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Calendar event created successfully.',
            'data' => new CalendarEventResource($event),
        ], 201);
    }

    public function show(int $id, CalendarEventServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CalendarEventResource($service->find($id)),
        ]);
    }

    public function update(int $id, CalendarUpdateRequest $request, CalendarEventServiceInterface $service): JsonResponse
    {
        $event = $service->update($id, CalendarEventMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Calendar event updated successfully.',
            'data' => new CalendarEventResource($event),
        ]);
    }

    public function destroy(int $id, CalendarEventServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Calendar event deleted successfully.',
        ]);
    }
}
