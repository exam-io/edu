<?php

namespace Modules\Institute\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Institute\Application\Services\InstituteBrandingService;
use Modules\Institute\Application\Services\InstituteConfigurationService;
use Modules\Institute\Application\Services\InstituteRegistrationService;
use Modules\Institute\Application\Services\InstituteService;
use Modules\Institute\Domain\Models\Institute;
use Modules\Institute\Http\Requests\RegisterInstituteRequest;
use Modules\Institute\Http\Requests\UpdateInstituteBrandingRequest;
use Modules\Institute\Http\Requests\UpdateInstituteRequest;
use Modules\Institute\Http\Resources\InstituteResource;

class InstituteController extends Controller
{
    public function register(
        RegisterInstituteRequest $request,
        InstituteRegistrationService $registrationService,
    ): JsonResponse
    {
        $institute = $registrationService->register($request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Institute registered successfully.',
            'data' => new InstituteResource($institute->load('currentAcademicSession')),
        ], 201);
    }

    public function current(InstituteService $instituteService): JsonResponse
    {
        $institute = $instituteService->getCurrentForTenant();

        if ($institute === null) {
            return response()->json([
                'success' => false,
                'message' => 'Institute not found for current tenant.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InstituteResource($institute),
        ]);
    }

    public function show(Institute $institute): JsonResponse
    {
        $this->assertTenantScope($institute);

        return response()->json([
            'success' => true,
            'data' => new InstituteResource($institute->load('currentAcademicSession')),
        ]);
    }

    public function update(
        UpdateInstituteRequest $request,
        Institute $institute,
        InstituteService $instituteService,
        InstituteConfigurationService $configurationService,
    ): JsonResponse
    {
        $this->assertTenantScope($institute);

        $institute = $instituteService->updateInstitute($institute, $request->validated());
        $institute = $configurationService->updateConfiguration($institute, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Institute updated successfully.',
            'data' => new InstituteResource($institute->load('currentAcademicSession')),
        ]);
    }

    public function updateBranding(
        UpdateInstituteBrandingRequest $request,
        Institute $institute,
        InstituteBrandingService $brandingService,
    ): JsonResponse
    {
        $this->assertTenantScope($institute);

        $institute = $brandingService->updateBranding($institute, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Institute branding updated successfully.',
            'data' => new InstituteResource($institute->load('currentAcademicSession')),
        ]);
    }

    public function onboarding(
        Institute $institute,
        InstituteConfigurationService $configurationService,
    ): JsonResponse {
        $this->assertTenantScope($institute);

        return response()->json([
            'success' => true,
            'data' => $configurationService->onboardingWizard($institute->load('currentAcademicSession')),
        ]);
    }

    private function assertTenantScope(Institute $institute): void
    {
        $tenantId = request()->attributes->get('tenant_id');

        abort_if((int) $tenantId !== (int) $institute->tenant_id, 404, 'Institute not found.');
    }
}
