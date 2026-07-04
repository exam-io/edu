interface ResultCardProps {
    title: string;
    score: number;
    rank: number;
    status: 'excellent' | 'good' | 'average' | 'needs_attention';
}

const statusText: Record<ResultCardProps['status'], string> = {
    excellent: 'Excellent',
    good: 'Good',
    average: 'Average',
    needs_attention: 'Needs attention',
};

export function ResultCard({ title, score, rank, status }: ResultCardProps) {
    return (
        <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <p className="text-xs font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">{title}</p>
            <p className="mt-2 font-mono text-3xl font-semibold">{Math.round(score)}%</p>
            <div className="mt-3 flex items-center gap-2 text-xs">
                <span className="rounded-full border border-[var(--color-border)] px-2 py-1">Rank #{rank}</span>
                <span className="rounded-full bg-[var(--color-surface-alt)] px-2 py-1">{statusText[status]}</span>
            </div>
        </article>
    );
}
