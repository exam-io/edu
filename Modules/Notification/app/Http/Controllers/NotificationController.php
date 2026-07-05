<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Notification\Application\Contracts\NotificationServiceInterface;
use Modules\Notification\Application\DTOs\DeviceTokenData;
use Modules\Notification\Http\Requests\NotificationIndexRequest;
use Modules\Notification\Http\Requests\RegisterDeviceTokenRequest;
use Modules\Notification\Http\Resources\TenantNotificationResource;

class NotificationController extends Controller
{
    public function index(NotificationIndexRequest $request, NotificationServiceInterface $service): JsonResponse
    {
        $perPage = (int) ($request->validated()['per_page'] ?? 20);
        $unread = (bool) ($request->validated()['unread'] ?? false);
        $data = $service->inbox($perPage, $unread);

        return response()->json([
            'success' => true,
            'data' => TenantNotificationResource::collection($data),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }

    public function registerDeviceToken(RegisterDeviceTokenRequest $request, NotificationServiceInterface $service): JsonResponse
    {
        $token = $service->registerDeviceToken(DeviceTokenData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Device token registered successfully.',
            'data' => [
                'id' => $token->id,
                'token' => $token->token,
                'device_type' => $token->device_type,
                'is_active' => (bool) $token->is_active,
            ],
        ], 201);
    }

    public function markRead(int $id, NotificationServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
            'data' => new TenantNotificationResource($service->markRead($id)),
        ]);
    }

    public function unreadCount(NotificationServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $service->unreadCount(),
            ],
        ]);
    }
}
