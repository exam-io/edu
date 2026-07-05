<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payment\Application\Contracts\PaymentServiceInterface;
use Modules\Payment\Application\DTOs\CapturePaymentIntentData;
use Modules\Payment\Application\DTOs\CreatePaymentIntentData;
use Modules\Payment\Application\DTOs\PaymentTransactionQueryData;
use Modules\Payment\Application\DTOs\WebhookPayloadData;
use Modules\Payment\Http\Requests\CapturePaymentIntentRequest;
use Modules\Payment\Http\Requests\CreatePaymentIntentRequest;
use Modules\Payment\Http\Requests\PaymentTransactionIndexRequest;
use Modules\Payment\Http\Resources\PaymentIntentResource;
use Modules\Payment\Http\Resources\PaymentTransactionResource;
use Modules\Payment\Http\Resources\PaymentWebhookLogResource;
use Modules\Tenant\Application\Services\TenantContextService;

class PaymentController extends Controller
{
    public function transactions(
        PaymentTransactionIndexRequest $request,
        TenantContextService $tenantContext,
        PaymentServiceInterface $service,
    ): JsonResponse {
        $items = $service->transactions(
            $tenantContext->requiredTenantId(),
            PaymentTransactionQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => PaymentTransactionResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function createIntent(
        CreatePaymentIntentRequest $request,
        TenantContextService $tenantContext,
        PaymentServiceInterface $service,
    ): JsonResponse {
        $intent = $service->createIntent(
            $tenantContext->requiredTenantId(),
            CreatePaymentIntentData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment intent created successfully.',
            'data' => new PaymentIntentResource($intent),
        ], 201);
    }

    public function captureIntent(
        int $id,
        CapturePaymentIntentRequest $request,
        TenantContextService $tenantContext,
        PaymentServiceInterface $service,
    ): JsonResponse {
        $transaction = $service->captureIntent(
            $tenantContext->requiredTenantId(),
            $id,
            CapturePaymentIntentData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment captured successfully.',
            'data' => new PaymentTransactionResource($transaction),
        ]);
    }

    public function receiveWebhook(
        string $provider,
        Request $request,
        TenantContextService $tenantContext,
        PaymentServiceInterface $service,
    ): JsonResponse {
        $log = $service->receiveWebhook(
            $tenantContext->requiredTenantId(),
            WebhookPayloadData::fromArray($provider, $request->all()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Webhook received and queued.',
            'data' => new PaymentWebhookLogResource($log),
        ], 202);
    }
}
