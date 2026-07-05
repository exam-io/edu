import { useEffect } from 'react';
import { useSubscriptionStore } from '@modules/subscription/store/subscriptionStore';

export function SubscriptionManagementPage() {
    const { plans, current, loading, error, initialize, renew } = useSubscriptionStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Subscription Management</h1>
                <p className="text-sm text-gray-500">Review your active plan and available subscription catalog.</p>
            </header>

            <article className="rounded border p-4">
                <h2 className="text-sm text-gray-500">Current Plan</h2>
                <p className="text-lg font-semibold">{current?.plan?.name ?? 'No active plan'}</p>
                <p className="text-sm text-gray-600">Status: {current?.status ?? 'n/a'}</p>
                <button className="mt-3 rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} onClick={() => void renew()} type="button">
                    Request Renewal
                </button>
            </article>

            <section className="grid gap-3 md:grid-cols-2">
                {plans.map((plan) => (
                    <article className="rounded border p-4" key={plan.id}>
                        <h3 className="font-semibold">{plan.name}</h3>
                        <p className="text-sm text-gray-600">{plan.code}</p>
                        <p className="text-sm">{plan.price_amount} {plan.currency} / {plan.billing_interval}</p>
                    </article>
                ))}
            </section>

            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
