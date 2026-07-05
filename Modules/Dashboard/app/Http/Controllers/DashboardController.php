<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Dashboard\Application\Contracts\DashboardServiceInterface;
use Modules\Dashboard\Application\DTOs\DashboardPreferenceData;
use Modules\Dashboard\Http\Requests\DashboardPreferenceUpdateRequest;
use Modules\Dashboard\Http\Resources\DashboardDefinitionResource;
use Modules\Dashboard\Http\Resources\UserDashboardPreferenceResource;

class DashboardController extends Controller
{
    public function me(DashboardServiceInterface $service): JsonResponse
    {
        $dashboard = $service->myDashboard();

        return response()->json([
            'success' => true,
            'data' => new DashboardDefinitionResource($dashboard),
        ]);
    }

    public function updatePreferences(
        DashboardPreferenceUpdateRequest $request,
        DashboardServiceInterface $service,
    ): JsonResponse {
        $preference = $service->updatePreference(DashboardPreferenceData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Dashboard preferences updated successfully.',
            'data' => new UserDashboardPreferenceResource($preference),
        ]);
    }
}
