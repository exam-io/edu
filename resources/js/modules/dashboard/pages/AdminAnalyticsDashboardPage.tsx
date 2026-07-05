import { useEffect } from 'react';
import { useRoleDashboardStore } from '@modules/dashboard/store/roleDashboardStore';

export function AdminAnalyticsDashboardPage() {
    const dashboard = useRoleDashboardStore((state) => state.dashboard);
    const loading = useRoleDashboardStore((state) => state.loading);
    const error = useRoleDashboardStore((state) => state.error);
    const loadDashboard = useRoleDashboardStore((state) => state.loadDashboard);

    useEffect(() => {
        void loadDashboard();
    }, [loadDashboard]);

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Admin BI Dashboard</h2>
                <p className="text-sm text-[var(--color-muted)]">Role-scoped analytics, reporting, and insights controls for tenant leadership.</p>
            </header>
            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading dashboard...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            {dashboard ? (
                <article className="rounded-md border border-[var(--color-border)] p-4">
                    <h3 className="font-semibold">{dashboard.name}</h3>
                    <p className="text-sm text-[var(--color-muted)]">Role: {dashboard.role_key}</p>
                    <ul className="mt-3 space-y-1 text-sm">
                        {(dashboard.widgets ?? []).map((widget) => (
                            <li key={widget.id}>{widget.title}</li>
                        ))}
                    </ul>
                </article>
            ) : null}
        </section>
    );
}
