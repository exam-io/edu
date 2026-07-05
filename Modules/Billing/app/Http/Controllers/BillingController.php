<?php

namespace Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Billing\Application\Contracts\BillingServiceInterface;
use Modules\Billing\Application\DTOs\InvoiceGenerationData;
use Modules\Billing\Application\DTOs\InvoiceQueryData;
use Modules\Billing\Application\DTOs\UpsertBillingProfileData;
use Modules\Billing\Http\Requests\GenerateInvoiceRequest;
use Modules\Billing\Http\Requests\InvoiceIndexRequest;
use Modules\Billing\Http\Requests\UpsertBillingProfileRequest;
use Modules\Billing\Http\Resources\BillingProfileResource;
use Modules\Billing\Http\Resources\InvoiceResource;
use Modules\Tenant\Application\Services\TenantContextService;

class BillingController extends Controller
{
    public function center(TenantContextService $tenantContext, BillingServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $service->center($tenantContext->requiredTenantId()),
        ]);
    }

    public function invoices(
        InvoiceIndexRequest $request,
        TenantContextService $tenantContext,
        BillingServiceInterface $service,
    ): JsonResponse {
        $items = $service->invoices(
            $tenantContext->requiredTenantId(),
            InvoiceQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function showInvoice(int $id, TenantContextService $tenantContext, BillingServiceInterface $service): JsonResponse
    {
        $invoice = $service->showInvoice($tenantContext->requiredTenantId(), $id);

        return response()->json([
            'success' => true,
            'data' => new InvoiceResource($invoice->load('lineItems')),
        ]);
    }

    public function generateInvoice(
        GenerateInvoiceRequest $request,
        TenantContextService $tenantContext,
        BillingServiceInterface $service,
    ): JsonResponse {
        $invoice = $service->requestInvoiceGeneration(
            $tenantContext->requiredTenantId(),
            InvoiceGenerationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Invoice generation queued.',
            'data' => new InvoiceResource($invoice),
        ], 202);
    }

    public function upsertProfile(
        UpsertBillingProfileRequest $request,
        TenantContextService $tenantContext,
        BillingServiceInterface $service,
    ): JsonResponse {
        $profile = $service->upsertProfile(
            $tenantContext->requiredTenantId(),
            UpsertBillingProfileData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Billing profile updated successfully.',
            'data' => new BillingProfileResource($profile),
        ]);
    }
}
