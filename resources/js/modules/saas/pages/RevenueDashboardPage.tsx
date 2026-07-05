import { FormEvent, useEffect } from 'react';
import { useSaaSStore } from '@modules/saas/store/saasStore';

export function RevenueDashboardPage() {
    const { dashboard, usage, loading, error, initialize, trackUsage } = useSaaSStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const onTrack = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const formData = new FormData(event.currentTarget);
        await trackUsage(String(formData.get('metric_key') ?? 'api_calls'), Number(formData.get('increment_by') ?? 1));
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Revenue Dashboard</h1>
                <p className="text-sm text-gray-500">Monitor recurring revenue and tenant-level usage counters.</p>
            </header>

            <div className="grid gap-4 md:grid-cols-3">
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">MRR</h2>
                    <p className="text-xl font-semibold">{dashboard?.mrr ?? 0}</p>
                </article>
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">ARR</h2>
                    <p className="text-xl font-semibold">{dashboard?.arr ?? 0}</p>
                </article>
                <article className="rounded border p-4">
                    <h2 className="text-sm text-gray-500">Active Subscribers</h2>
                    <p className="text-xl font-semibold">{dashboard?.active_subscribers ?? 0}</p>
                </article>
            </div>

            <form className="flex items-end gap-2" onSubmit={onTrack}>
                <label className="grid gap-1 text-sm">
                    Metric
                    <input className="rounded border p-2" defaultValue="api_calls" name="metric_key" />
                </label>
                <label className="grid gap-1 text-sm">
                    Increment
                    <input className="rounded border p-2" defaultValue={1} min={0} name="increment_by" step="1" type="number" />
                </label>
                <button className="rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} type="submit">
                    Track Usage
                </button>
            </form>

            <div className="overflow-x-auto rounded border">
                <table className="min-w-full text-sm">
                    <thead className="bg-gray-100">
                        <tr>
                            <th className="px-3 py-2 text-left">Metric</th>
                            <th className="px-3 py-2 text-left">Period</th>
                            <th className="px-3 py-2 text-left">Counter</th>
                        </tr>
                    </thead>
                    <tbody>
                        {usage.map((item) => (
                            <tr key={item.id}>
                                <td className="px-3 py-2">{item.metric_key}</td>
                                <td className="px-3 py-2">{item.period_key}</td>
                                <td className="px-3 py-2">{item.counter}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
