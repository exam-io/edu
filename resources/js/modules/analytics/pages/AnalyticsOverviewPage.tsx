import { useEffect } from 'react';
import { ChartCard } from '@components/cards/ChartCard';
import { StatCard } from '@components/cards/StatCard';
import { Activity } from 'lucide-react';
import { useAnalyticsStore } from '@modules/analytics/store/analyticsStore';

export function AnalyticsOverviewPage() {
    const events = useAnalyticsStore((state) => state.events);
    const snapshots = useAnalyticsStore((state) => state.snapshots);
    const loading = useAnalyticsStore((state) => state.loading);
    const error = useAnalyticsStore((state) => state.error);
    const fetchEvents = useAnalyticsStore((state) => state.fetchEvents);
    const fetchSnapshots = useAnalyticsStore((state) => state.fetchSnapshots);

    useEffect(() => {
        void fetchEvents();
        void fetchSnapshots();
    }, [fetchEvents, fetchSnapshots]);

    const totalEvents = events.length;
    const lastMetric = snapshots[snapshots.length - 1]?.metric_value ?? 0;

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Analytics Overview</h2>
                <p className="text-sm text-[var(--color-muted)]">Real-time tenant analytics stream and aggregated metrics.</p>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Tracked Events" value={String(totalEvents)} icon={Activity} sparkline={[2, 3, 4, 3, 5, 7, 8, 9, 8, 10, 9, 11]} />
                <StatCard label="Latest Metric" value={String(lastMetric)} icon={Activity} sparkline={[1, 2, 3, 3, 4, 6, 6, 8, 7, 8, 9, 9]} />
            </div>

            <ChartCard title="Recent Events" subtitle="Latest tracked events from all modules">
                {loading ? <p className="text-sm text-[var(--color-muted)]">Loading analytics data...</p> : null}
                {error ? <p className="text-sm text-red-600">{error}</p> : null}
                <ul className="space-y-2 text-sm">
                    {events.slice(0, 8).map((event) => (
                        <li key={event.id} className="rounded-md border border-[var(--color-border)] px-3 py-2">
                            <span className="font-medium">{event.event_name}</span>
                            <span className="ml-2 text-[var(--color-muted)]">{event.source_module}</span>
                        </li>
                    ))}
                </ul>
            </ChartCard>
        </section>
    );
}
