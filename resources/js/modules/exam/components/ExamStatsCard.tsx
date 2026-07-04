import type { ReactNode } from 'react';

interface ExamStatsCardProps {
    title: string;
    value: ReactNode;
}

export function ExamStatsCard({ title, value }: ExamStatsCardProps) {
    return (
        <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <p className="text-xs text-[var(--color-muted)]">{title}</p>
            <p className="mt-1 text-2xl font-semibold">{value}</p>
        </article>
    );
}
