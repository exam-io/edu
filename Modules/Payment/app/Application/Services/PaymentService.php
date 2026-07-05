<?php

namespace Modules\Payment\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Payment\Application\Contracts\PaymentProviderResolverInterface;
use Modules\Payment\Application\Contracts\PaymentServiceInterface;
use Modules\Payment\Application\DTOs\CapturePaymentIntentData;
use Modules\Payment\Application\DTOs\CreatePaymentIntentData;
use Modules\Payment\Application\DTOs\PaymentTransactionQueryData;
use Modules\Payment\Application\DTOs\WebhookPayloadData;
use Modules\Payment\Domain\Events\PaymentWebhookReceived;
use Modules\Payment\Domain\Models\PaymentIntent;
use Modules\Payment\Domain\Models\PaymentTransaction;
use Modules\Payment\Domain\Models\PaymentWebhookLog;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(private readonly PaymentProviderResolverInterface $resolver) {}

    public function transactions(int $tenantId, PaymentTransactionQueryData $query): LengthAwarePaginator
    {
        $builder = PaymentTransaction::query()->where('tenant_id', $tenantId)->latest('id');

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->provider !== null && $query->provider !== '') {
            $builder->where('provider', $query->provider);
        }

        if ($query->search !== null && $query->search !== '') {
            $builder->where('provider_transaction_id', 'like', '%' . $query->search . '%');
        }

        return $builder->paginate($query->perPage);
    }

    public function createIntent(int $tenantId, CreatePaymentIntentData $data): PaymentIntent
    {
        $provider = $this->resolver->resolve($data->provider);
        $result = $provider->createIntent([
            'amount' => $data->amount,
            'currency' => $data->currency,
            'invoice_id' => $data->invoiceId,
            'meta' => $data->meta,
        ]);

        return PaymentIntent::query()->create([
            'tenant_id' => $tenantId,
            'invoice_id' => $data->invoiceId,
            'provider' => $provider->providerKey(),
            'provider_intent_id' => (string) $result['provider_intent_id'],
            'currency' => $data->currency,
            'amount' => $data->amount,
            'status' => (string) ($result['status'] ?? 'requires_capture'),
            'client_secret' => isset($result['client_secret']) ? (string) $result['client_secret'] : null,
            'meta' => is_array($result['meta'] ?? null) ? $result['meta'] : [],
        ]);
    }

    public function captureIntent(int $tenantId, int $intentId, CapturePaymentIntentData $data): PaymentTransaction
    {
        $intent = PaymentIntent::query()->where('tenant_id', $tenantId)->findOrFail($intentId);
        $provider = $this->resolver->resolve($intent->provider);

        $result = $provider->captureIntent((string) $intent->provider_intent_id, [
            'amount' => $data->amount,
            'meta' => $data->meta,
        ]);

        $intent->update([
            'status' => (string) ($result['status'] ?? 'succeeded'),
        ]);

        return PaymentTransaction::query()->create([
            'tenant_id' => $tenantId,
            'payment_intent_id' => $intent->id,
            'provider' => $intent->provider,
            'provider_transaction_id' => (string) ($result['provider_transaction_id'] ?? ''),
            'status' => (string) ($result['status'] ?? 'succeeded'),
            'amount' => $data->amount ?? $intent->amount,
            'currency' => $intent->currency,
            'processed_at' => now(),
            'meta' => is_array($result['meta'] ?? null) ? $result['meta'] : [],
        ]);
    }

    public function receiveWebhook(int $tenantId, WebhookPayloadData $data): PaymentWebhookLog
    {
        $log = PaymentWebhookLog::query()->create([
            'tenant_id' => $tenantId,
            'provider' => $data->provider,
            'event_key' => $data->eventKey,
            'payload' => $data->payload,
            'status' => 'queued',
            'processed_at' => null,
            'error_message' => null,
        ]);

        event(new PaymentWebhookReceived($tenantId, $log->id));

        return $log;
    }
}
