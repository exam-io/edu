import { useResultStore } from '@modules/assessment/store/resultStore';

export function ResultScreenPage() {
    const result = useResultStore((state) => state.result);

    return (
        <section className="space-y-3 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h2 className="text-xl font-semibold">Result Screen</h2>
            {result ? (
                <>
                    <p className="text-sm">Score: {result.score}</p>
                    <p className="text-sm">Percentage: {result.percentage}%</p>
                    <p className="text-sm">Rank: {result.rank ?? 'N/A'}</p>
                </>
            ) : (
                <p className="text-sm text-[var(--color-muted)]">No result loaded yet.</p>
            )}
        </section>
    );
}
