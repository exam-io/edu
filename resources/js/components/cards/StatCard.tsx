import type { LucideIcon } from 'lucide-react';

interface StatCardProps {
    label: string;
    value: string;
    trend?: string;
    trendUp?: boolean;
    sparkline?: number[];
    icon: LucideIcon;
}

export function StatCard({ label, value, trend, trendUp = true, sparkline = [], icon: Icon }: StatCardProps) {
    const max = Math.max(...sparkline, 1);

    return (
        <article className="group relative overflow-hidden rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[var(--shadow-elevated)]">
            <div className="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-[color-mix(in_oklab,var(--color-primary)_16%,transparent)] blur-2xl" />

            <div className="relative flex items-start justify-between gap-3">
                <div>
                    <p className="text-xs font-medium uppercase tracking-[0.16em] text-[var(--color-muted)]">{label}</p>
                    <p className="mt-2 font-mono text-3xl font-semibold leading-none">{value}</p>
                    {trend ? (
                        <p className={`mt-2 text-xs font-semibold ${trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'}`}>
                            {trend}
                        </p>
                    ) : null}
                </div>
                <span className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-2 text-[var(--color-primary)]">
                    <Icon size={18} />
                </span>
            </div>

            <div className="relative mt-4 flex h-8 items-end gap-1">
                {sparkline.slice(-12).map((point, index) => (
                    <span
                        key={`${label}-spark-${index}`}
                        className="w-full rounded-full bg-[color-mix(in_oklab,var(--color-primary)_58%,white)]"
                        style={{ height: `${Math.max((point / max) * 100, 12)}%` }}
                    />
                ))}
            </div>
        </article>
    );
}
