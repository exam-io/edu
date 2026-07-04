interface ProgressCardProps {
    title: string;
    value: number;
    subtitle?: string;
}

export function ProgressCard({ title, value, subtitle }: ProgressCardProps) {
    return (
        <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <p className="text-sm font-semibold">{title}</p>
            <div className="mt-3 h-2.5 overflow-hidden rounded-full bg-[var(--color-surface-alt)]">
                <div
                    className="h-full rounded-full bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-primary)] transition-all duration-300"
                    style={{ width: `${Math.min(Math.max(value, 0), 100)}%` }}
                />
            </div>
            <div className="mt-2 flex items-center justify-between">
                <p className="font-mono text-sm font-semibold">{Math.round(value)}%</p>
                <p className="text-xs text-[var(--color-muted)]">{subtitle}</p>
            </div>
        </article>
    );
}
