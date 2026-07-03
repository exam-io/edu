<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Contracts\ProgramServiceInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Http\Requests\AcademicIndexRequest;
use Modules\Academic\Http\Requests\ProgramStoreRequest;
use Modules\Academic\Http\Requests\ProgramUpdateRequest;
use Modules\Academic\Http\Resources\ProgramResource;

class ProgramController extends Controller
{
    public function index(AcademicIndexRequest $request, ProgramServiceInterface $service): JsonResponse
    {
        $data = $service->list(AcademicListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => ProgramResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(ProgramStoreRequest $request, ProgramServiceInterface $service): JsonResponse
    {
        $program = $service->create(AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Program created successfully.',
            'data' => new ProgramResource($program),
        ], 201);
    }

    public function show(int $id, ProgramServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ProgramResource($service->find($id)),
        ]);
    }

    public function update(int $id, ProgramUpdateRequest $request, ProgramServiceInterface $service): JsonResponse
    {
        $program = $service->update($id, AcademicMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Program updated successfully.',
            'data' => new ProgramResource($program),
        ]);
    }

    public function destroy(int $id, ProgramServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Program deleted successfully.',
        ]);
    }
}
