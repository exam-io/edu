import type { ReactNode } from 'react';

interface EmptyStateProps {
    title: string;
    description: string;
    illustration?: ReactNode;
    primaryAction?: ReactNode;
    secondaryAction?: ReactNode;
}

export function EmptyState({
    title,
    description,
    illustration,
    primaryAction,
    secondaryAction,
}: EmptyStateProps) {
    return (
        <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-8 text-center shadow-[var(--shadow-soft)]">
            <div className="mx-auto flex max-w-lg flex-col items-center gap-4">
                <div className="rounded-2xl bg-[var(--color-surface-alt)] p-4 text-[var(--color-primary)]">
                    {illustration ?? <span className="text-3xl">*</span>}
                </div>
                <div>
                    <h3 className="text-lg font-semibold">{title}</h3>
                    <p className="mt-2 text-sm text-[var(--color-muted)]">{description}</p>
                </div>
                <div className="flex flex-wrap items-center justify-center gap-3">
                    {primaryAction}
                    {secondaryAction}
                </div>
            </div>
        </section>
    );
}
