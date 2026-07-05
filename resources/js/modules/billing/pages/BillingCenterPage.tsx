import { FormEvent, useEffect } from 'react';
import { useBillingStore } from '@modules/billing/store/billingStore';

export function BillingCenterPage() {
    const { center, invoices, profile, loading, error, initialize, saveProfile } = useBillingStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const formData = new FormData(event.currentTarget);
        await saveProfile({
            legal_name: String(formData.get('legal_name') ?? ''),
            email: String(formData.get('email') ?? ''),
            currency: String(formData.get('currency') ?? 'USD'),
        });
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Billing Center</h1>
                <p className="text-sm text-gray-500">Track invoice health and update billing profile settings.</p>
            </header>

            <div className="grid gap-4 md:grid-cols-3">
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">Outstanding</h2>
                    <p className="text-xl font-semibold">{center?.outstanding_total ?? 0}</p>
                </article>
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">MRR</h2>
                    <p className="text-xl font-semibold">{center?.mrr ?? 0}</p>
                </article>
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">Latest Invoice</h2>
                    <p className="text-xl font-semibold">{center?.latest_invoice?.number ?? 'n/a'}</p>
                </article>
            </div>

            <form className="grid max-w-xl gap-3" key={profile?.id ?? 0} onSubmit={onSubmit}>
                <input className="rounded border p-2" defaultValue={profile?.legal_name ?? ''} name="legal_name" placeholder="Legal name" />
                <input className="rounded border p-2" defaultValue={profile?.email ?? ''} name="email" placeholder="Billing email" />
                <input className="rounded border p-2" defaultValue={profile?.currency ?? 'USD'} name="currency" placeholder="Currency" />
                <button className="w-fit rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} type="submit">
                    {loading ? 'Saving...' : 'Save Profile'}
                </button>
            </form>

            <section className="space-y-2">
                <h2 className="text-lg font-medium">Recent Invoices</h2>
                <div className="overflow-x-auto rounded border">
                    <table className="min-w-full text-sm">
                        <thead className="bg-gray-100">
                            <tr>
                                <th className="px-3 py-2 text-left">Number</th>
                                <th className="px-3 py-2 text-left">Status</th>
                                <th className="px-3 py-2 text-left">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            {invoices.map((invoice) => (
                                <tr key={invoice.id}>
                                    <td className="px-3 py-2">{invoice.number}</td>
                                    <td className="px-3 py-2">{invoice.status}</td>
                                    <td className="px-3 py-2">{invoice.total_amount}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </section>

            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
