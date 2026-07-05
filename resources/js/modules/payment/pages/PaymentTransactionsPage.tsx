import { FormEvent, useEffect } from 'react';
import { usePaymentStore } from '@modules/payment/store/paymentStore';

export function PaymentTransactionsPage() {
    const { transactions, latestIntent, loading, error, initialize, createIntent } = usePaymentStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const amount = Number(new FormData(event.currentTarget).get('amount') ?? 0);
        await createIntent(amount);
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Payments</h1>
                <p className="text-sm text-gray-500">Create payment intents and monitor payment transactions.</p>
            </header>

            <form className="flex items-end gap-2" onSubmit={onSubmit}>
                <label className="grid gap-1 text-sm">
                    Amount
                    <input className="rounded border p-2" defaultValue={49.99} min={0.01} name="amount" step="0.01" type="number" />
                </label>
                <button className="rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} type="submit">
                    Create Intent
                </button>
            </form>

            {latestIntent ? (
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">Latest Intent</h2>
                    <p>{latestIntent.id} • {latestIntent.status} • {latestIntent.amount} {latestIntent.currency}</p>
                </article>
            ) : null}

            <div className="overflow-x-auto rounded border">
                <table className="min-w-full text-sm">
                    <thead className="bg-gray-100">
                        <tr>
                            <th className="px-3 py-2 text-left">Provider</th>
                            <th className="px-3 py-2 text-left">Transaction</th>
                            <th className="px-3 py-2 text-left">Status</th>
                            <th className="px-3 py-2 text-left">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {transactions.map((transaction) => (
                            <tr key={transaction.id}>
                                <td className="px-3 py-2">{transaction.provider}</td>
                                <td className="px-3 py-2">{transaction.provider_transaction_id}</td>
                                <td className="px-3 py-2">{transaction.status}</td>
                                <td className="px-3 py-2">{transaction.amount} {transaction.currency}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
