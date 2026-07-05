<?php

namespace Modules\Payment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Payment\Application\DTOs\CapturePaymentIntentData;
use Modules\Payment\Application\DTOs\CreatePaymentIntentData;
use Modules\Payment\Application\DTOs\PaymentTransactionQueryData;
use Modules\Payment\Application\DTOs\WebhookPayloadData;
use Modules\Payment\Domain\Models\PaymentIntent;
use Modules\Payment\Domain\Models\PaymentTransaction;
use Modules\Payment\Domain\Models\PaymentWebhookLog;

interface PaymentServiceInterface
{
    public function transactions(int $tenantId, PaymentTransactionQueryData $query): LengthAwarePaginator;

    public function createIntent(int $tenantId, CreatePaymentIntentData $data): PaymentIntent;

    public function captureIntent(int $tenantId, int $intentId, CapturePaymentIntentData $data): PaymentTransaction;

    public function receiveWebhook(int $tenantId, WebhookPayloadData $data): PaymentWebhookLog;
}
