<?php

namespace Modules\QuestionBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\QuestionBank\Application\Contracts\QuestionBankServiceInterface;
use Modules\QuestionBank\Application\DTOs\QuestionSetListQueryData;
use Modules\QuestionBank\Application\DTOs\QuestionSetMutationData;
use Modules\QuestionBank\Http\Requests\QuestionSetIndexRequest;
use Modules\QuestionBank\Http\Requests\QuestionSetStoreRequest;
use Modules\QuestionBank\Http\Requests\QuestionSetUpdateRequest;
use Modules\QuestionBank\Http\Resources\QuestionSetResource;

class QuestionSetController extends Controller
{
    public function index(QuestionSetIndexRequest $request, QuestionBankServiceInterface $service): JsonResponse
    {
        $data = $service->list(QuestionSetListQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => QuestionSetResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function store(QuestionSetStoreRequest $request, QuestionBankServiceInterface $service): JsonResponse
    {
        $set = $service->create(QuestionSetMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Question set created successfully.',
            'data' => new QuestionSetResource($set),
        ], 201);
    }

    public function show(int $id, QuestionBankServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new QuestionSetResource($service->find($id)),
        ]);
    }

    public function update(int $id, QuestionSetUpdateRequest $request, QuestionBankServiceInterface $service): JsonResponse
    {
        $set = $service->update($id, QuestionSetMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Question set updated successfully.',
            'data' => new QuestionSetResource($set),
        ]);
    }

    public function destroy(int $id, QuestionBankServiceInterface $service): JsonResponse
    {
        $service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Question set deleted successfully.',
        ]);
    }
}
