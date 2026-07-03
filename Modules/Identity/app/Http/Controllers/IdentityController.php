<?php

namespace Modules\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IdentityController extends Controller
{
    public function index(): JsonResponse
    {
        $user = request()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'module' => 'identity',
                'user' => $user?->only(['id', 'tenant_id', 'name', 'email', 'status']),
            ],
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'editable_fields' => ['name', 'email', 'language', 'theme', 'timezone'],
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Identity resource creation is not supported from this endpoint.',
        ], 405);
    }

    public function show(string $id): JsonResponse
    {
        $user = $this->resolveVisibleUser((int) $id);

        return response()->json([
            'success' => true,
            'data' => [
                ...$user->only(['id', 'tenant_id', 'name', 'email', 'status']),
                'settings' => $user->settings?->only(['language', 'theme', 'timezone']),
            ],
        ]);
    }

    public function edit(string $id): JsonResponse
    {
        $user = $this->resolveVisibleUser((int) $id);

        return response()->json([
            'success' => true,
            'data' => [
                'profile' => [
                    ...$user->only(['id', 'tenant_id', 'name', 'email', 'status']),
                    'settings' => $user->settings?->only(['language', 'theme', 'timezone']),
                ],
                'editable_fields' => ['name', 'email', 'language', 'theme', 'timezone'],
            ],
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = $this->resolveVisibleUser((int) $id);

        $payload = Validator::make($request->all(), [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'language' => ['sometimes', Rule::in(['en', 'hi'])],
            'theme' => ['sometimes', Rule::in(['light', 'dark'])],
            'timezone' => ['sometimes', 'timezone'],
        ])->validate();

        $user->fill([
            'name' => $payload['name'] ?? $user->name,
            'email' => $payload['email'] ?? $user->email,
        ]);
        $user->save();

        if (isset($payload['language']) || isset($payload['theme']) || isset($payload['timezone'])) {
            $settings = $user->settings()->firstOrCreate(
                ['user_id' => $user->id],
                ['language' => 'en', 'theme' => 'light', 'timezone' => 'UTC'],
            );

            $settings->fill([
                'language' => $payload['language'] ?? $settings->language,
                'theme' => $payload['theme'] ?? $settings->theme,
                'timezone' => $payload['timezone'] ?? $settings->timezone,
            ]);
            $settings->save();
        }

        $user->load('settings');

        return response()->json([
            'success' => true,
            'message' => 'Identity profile updated successfully.',
            'data' => [
                ...$user->only(['id', 'tenant_id', 'name', 'email', 'status']),
                'settings' => $user->settings?->only(['language', 'theme', 'timezone']),
            ],
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Identity deletion is not supported from this endpoint.',
            'resource_id' => $id,
        ], 405);
    }

    private function resolveVisibleUser(int $id): User
    {
        /** @var User|null $currentUser */
        $currentUser = request()->user();

        abort_if($currentUser === null, 401, 'Unauthenticated.');
        abort_if((int) $currentUser->id !== $id, 404, 'Identity not found.');

        return $currentUser->loadMissing('settings');
    }
}
