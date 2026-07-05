import { useEffect } from 'react';
import { FileClock } from 'lucide-react';
import { StatCard } from '@components/cards/StatCard';
import { useAuditStore } from '@modules/audit/store/auditStore';

export function AuditTrailPage() {
    const logs = useAuditStore((state) => state.logs);
    const loading = useAuditStore((state) => state.loading);
    const error = useAuditStore((state) => state.error);
    const initialize = useAuditStore((state) => state.initialize);
    const logDashboardAccess = useAuditStore((state) => state.logDashboardAccess);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <header className="flex items-center justify-between">
                <div>
                    <h2 className="text-xl font-semibold">Audit Trail</h2>
                    <p className="text-sm text-[var(--color-muted)]">Track critical actions across security, monitoring, and operations workflows.</p>
                </div>
                <button
                    className="rounded-md border border-[var(--color-border)] px-3 py-2 text-sm"
                    onClick={() => {
                        void logDashboardAccess();
                    }}
                    type="button"
                >
                    Record Access Event
                </button>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Total Audit Logs" value={String(logs.length)} icon={FileClock} />
            </div>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading audit stream...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <div className="rounded-xl border border-[var(--color-border)] bg-white/60 p-4">
                <h3 className="mb-2 text-sm font-semibold">Recent Entries</h3>
                <ul className="space-y-2 text-sm">
                    {logs.map((entry) => (
                        <li className="rounded-md border border-[var(--color-border)] px-3 py-2" key={entry.id}>
                            <span className="font-medium">{entry.action}</span>
                            <span className="ml-2 text-[var(--color-muted)]">{entry.resource_type}</span>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}
