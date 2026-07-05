<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Communication\Application\Contracts\CommunicationServiceInterface;
use Modules\Communication\Application\DTOs\CommunicationHistoryQueryData;
use Modules\Communication\Http\Requests\CommunicationHistoryIndexRequest;
use Modules\Communication\Http\Resources\CommunicationHistoryResource;

class CommunicationHistoryController extends Controller
{
    public function index(CommunicationHistoryIndexRequest $request, CommunicationServiceInterface $service): JsonResponse
    {
        $data = $service->listHistory(CommunicationHistoryQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => CommunicationHistoryResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }
}
