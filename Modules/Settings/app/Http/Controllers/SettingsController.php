<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Settings\Application\Contracts\UserSettingsServiceInterface;
use Modules\Settings\Http\Requests\UpsertSettingsRequest;
use Modules\Settings\Http\Resources\UserSettingResource;

class SettingsController extends Controller
{
    public function __construct(
        private readonly UserSettingsServiceInterface $settingsService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserSettingResource($this->settingsService->currentForAuthenticatedUser()),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'editable_fields' => ['language', 'theme', 'timezone'],
                'language_options' => ['en', 'hi'],
                'theme_options' => ['light', 'dark'],
            ],
        ]);
    }

    public function store(UpsertSettingsRequest $request): JsonResponse
    {
        $setting = $this->settingsService->upsertForAuthenticatedUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.',
            'data' => new UserSettingResource($setting),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserSettingResource($this->settingsService->showForAuthenticatedUser($id)),
        ]);
    }

    public function edit(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'settings' => new UserSettingResource($this->settingsService->showForAuthenticatedUser($id)),
                'editable_fields' => ['language', 'theme', 'timezone'],
                'language_options' => ['en', 'hi'],
                'theme_options' => ['light', 'dark'],
            ],
        ]);
    }

    public function update(UpsertSettingsRequest $request, int $id): JsonResponse
    {
        $setting = $this->settingsService->updateForAuthenticatedUser($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
            'data' => new UserSettingResource($setting),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $setting = $this->settingsService->resetForAuthenticatedUser($id);

        return response()->json([
            'success' => true,
            'message' => 'Settings reset to defaults successfully.',
            'data' => new UserSettingResource($setting),
        ]);
    }
}
