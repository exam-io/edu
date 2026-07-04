import type { ReactNode } from 'react';

interface PageHeaderProps {
    title: string;
    description: string;
    actions?: ReactNode;
}

export function PageHeader({ title, description, actions }: PageHeaderProps) {
    return (
        <header className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[linear-gradient(110deg,color-mix(in_oklab,var(--color-primary)_15%,var(--color-surface))_0%,var(--color-surface)_42%,color-mix(in_oklab,var(--color-secondary)_16%,var(--color-surface))_100%)] p-5 shadow-[var(--shadow-soft)]">
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight sm:text-3xl">{title}</h1>
                    <p className="mt-2 max-w-2xl text-sm text-[var(--color-muted)]">{description}</p>
                </div>
                {actions}
            </div>
        </header>
    );
}
