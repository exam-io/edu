import { useEffect } from 'react';
import { ShieldCheck } from 'lucide-react';
import { StatCard } from '@components/cards/StatCard';
import { useSystemStore } from '@modules/system/store/systemStore';

export function SystemSecurityPage() {
    const policy = useSystemStore((state) => state.policy);
    const checks = useSystemStore((state) => state.checks);
    const loading = useSystemStore((state) => state.loading);
    const error = useSystemStore((state) => state.error);
    const initialize = useSystemStore((state) => state.initialize);
    const refreshChecks = useSystemStore((state) => state.refreshChecks);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const healthyCount = checks.filter((item) => item.status === 'ok').length;

    return (
        <section className="space-y-4">
            <header className="flex items-center justify-between">
                <div>
                    <h2 className="text-xl font-semibold">System Security and Health</h2>
                    <p className="text-sm text-[var(--color-muted)]">Production hardening controls and live subsystem checks.</p>
                </div>
                <button
                    className="rounded-md border border-[var(--color-border)] px-3 py-2 text-sm"
                    onClick={() => {
                        void refreshChecks();
                    }}
                    type="button"
                >
                    Refresh Health
                </button>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Session TTL (min)" value={String(policy?.session_ttl_minutes ?? '-')} icon={ShieldCheck} />
                <StatCard label="MFA Enforcement" value={policy?.force_mfa ? 'Enabled' : 'Optional'} icon={ShieldCheck} />
                <StatCard label="Healthy Checks" value={`${healthyCount}/${checks.length}`} icon={ShieldCheck} />
                <StatCard label="HSTS" value={policy?.strict_transport_security ? 'Enabled' : 'Disabled'} icon={ShieldCheck} />
            </div>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading system controls...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <div className="rounded-xl border border-[var(--color-border)] bg-white/60 p-4">
                <h3 className="mb-3 text-sm font-semibold">Health Check Timeline</h3>
                <ul className="space-y-2 text-sm">
                    {checks.map((check) => (
                        <li className="rounded-md border border-[var(--color-border)] px-3 py-2" key={check.id}>
                            <span className="font-medium">{check.check_name}</span>
                            <span className="ml-2 text-[var(--color-muted)]">{check.status}</span>
                            <p className="text-xs text-[var(--color-muted)]">{check.checked_at}</p>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}
