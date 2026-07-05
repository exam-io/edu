import { useEffect } from 'react';
import { Activity } from 'lucide-react';
import { StatCard } from '@components/cards/StatCard';
import { useMonitoringStore } from '@modules/monitoring/store/monitoringStore';

export function MonitoringDashboardPage() {
    const metrics = useMonitoringStore((state) => state.metrics);
    const incidents = useMonitoringStore((state) => state.incidents);
    const loading = useMonitoringStore((state) => state.loading);
    const error = useMonitoringStore((state) => state.error);
    const initialize = useMonitoringStore((state) => state.initialize);
    const triggerAggregation = useMonitoringStore((state) => state.triggerAggregation);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <header className="flex items-center justify-between">
                <div>
                    <h2 className="text-xl font-semibold">Monitoring Center</h2>
                    <p className="text-sm text-[var(--color-muted)]">Observe key metrics and incident thresholds for production stability.</p>
                </div>
                <button
                    className="rounded-md border border-[var(--color-border)] px-3 py-2 text-sm"
                    onClick={() => {
                        void triggerAggregation();
                    }}
                    type="button"
                >
                    Run Aggregation
                </button>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Metric Snapshots" value={String(metrics.length)} icon={Activity} />
                <StatCard label="Open Incidents" value={String(incidents.filter((item) => item.status === 'open').length)} icon={Activity} />
            </div>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading monitoring telemetry...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <div className="grid gap-3 lg:grid-cols-2">
                <div className="rounded-xl border border-[var(--color-border)] bg-white/60 p-4">
                    <h3 className="mb-2 text-sm font-semibold">Recent Metrics</h3>
                    <ul className="space-y-2 text-sm">
                        {metrics.map((metric) => (
                            <li className="rounded-md border border-[var(--color-border)] px-3 py-2" key={metric.id}>
                                <span className="font-medium">{metric.metric_key}</span>
                                <span className="ml-2">{metric.value}</span>
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="rounded-xl border border-[var(--color-border)] bg-white/60 p-4">
                    <h3 className="mb-2 text-sm font-semibold">Incidents</h3>
                    <ul className="space-y-2 text-sm">
                        {incidents.map((incident) => (
                            <li className="rounded-md border border-[var(--color-border)] px-3 py-2" key={incident.id}>
                                <span className="font-medium">{incident.metric_key}</span>
                                <span className="ml-2 text-[var(--color-muted)]">{incident.severity}</span>
                            </li>
                        ))}
                    </ul>
                </div>
            </div>
        </section>
    );
}
