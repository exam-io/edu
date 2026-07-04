import type { ReactNode } from 'react';

interface ChartCardProps {
    title: string;
    subtitle?: string;
    actions?: ReactNode;
    children: ReactNode;
}

export function ChartCard({ title, subtitle, actions, children }: ChartCardProps) {
    return (
        <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <header className="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h3 className="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">{title}</h3>
                    {subtitle ? <p className="mt-1 text-sm text-[var(--color-muted)]">{subtitle}</p> : null}
                </div>
                {actions}
            </header>
            {children}
        </section>
    );
}
