<?php

namespace Modules\Institute\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Institute\Application\Services\AcademicSessionService;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Institute\Domain\Models\Institute;
use Modules\Institute\Http\Requests\StoreAcademicSessionRequest;
use Modules\Institute\Http\Requests\UpdateAcademicSessionRequest;
use Modules\Institute\Http\Resources\AcademicSessionResource;

class AcademicSessionController extends Controller
{
    public function index(Institute $institute, AcademicSessionService $service): JsonResponse
    {
        $this->assertTenantScope($institute);

        return response()->json([
            'success' => true,
            'data' => AcademicSessionResource::collection($service->listForInstitute($institute)),
        ]);
    }

    public function store(
        StoreAcademicSessionRequest $request,
        Institute $institute,
        AcademicSessionService $service,
    ): JsonResponse {
        $this->assertTenantScope($institute);

        $session = $service->create($institute, $request->validated(), $request->user()?->id);

        return response()->json([
            'success' => true,
            'message' => 'Academic session created successfully.',
            'data' => new AcademicSessionResource($session),
        ], 201);
    }

    public function show(Institute $institute, AcademicSession $academicSession): JsonResponse
    {
        $this->assertTenantScope($institute);
        $this->assertSessionOwnership($institute, $academicSession);

        return response()->json([
            'success' => true,
            'data' => new AcademicSessionResource($academicSession),
        ]);
    }

    public function update(
        UpdateAcademicSessionRequest $request,
        Institute $institute,
        AcademicSession $academicSession,
        AcademicSessionService $service,
    ): JsonResponse {
        $this->assertTenantScope($institute);
        $this->assertSessionOwnership($institute, $academicSession);

        $session = $service->update($institute, $academicSession, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Academic session updated successfully.',
            'data' => new AcademicSessionResource($session),
        ]);
    }

    public function destroy(
        Institute $institute,
        AcademicSession $academicSession,
        AcademicSessionService $service,
    ): JsonResponse {
        $this->assertTenantScope($institute);
        $this->assertSessionOwnership($institute, $academicSession);

        $service->delete($institute, $academicSession);

        return response()->json([
            'success' => true,
            'message' => 'Academic session deleted successfully.',
        ]);
    }

    private function assertTenantScope(Institute $institute): void
    {
        $tenantId = request()->attributes->get('tenant_id');
        abort_if((int) $tenantId !== (int) $institute->tenant_id, 404, 'Institute not found.');
    }

    private function assertSessionOwnership(Institute $institute, AcademicSession $session): void
    {
        abort_if((int) $session->institute_id !== (int) $institute->id, 404, 'Academic session not found.');
    }
}
