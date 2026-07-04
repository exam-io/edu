import { useEffect } from 'react';
import { useExamStore } from '@modules/exam/store/examStore';

export function ExamOverviewPage() {
    const overview = useExamStore((state) => state.overview);
    const loading = useExamStore((state) => state.loading);
    const error = useExamStore((state) => state.error);
    const fetchOverview = useExamStore((state) => state.fetchOverview);

    useEffect(() => {
        void fetchOverview();
    }, [fetchOverview]);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Exam Overview</h2>
            {loading ? <p className="text-sm">Loading exam overview...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3 md:grid-cols-3">
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Total Assessments</p>
                    <p className="mt-1 text-2xl font-semibold">{overview?.total_assessments ?? 0}</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Published</p>
                    <p className="mt-1 text-2xl font-semibold">{overview?.published_assessments ?? 0}</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Types</p>
                    <p className="mt-1 text-2xl font-semibold">{overview?.exam_type_breakdown.length ?? 0}</p>
                </article>
            </div>
            <div className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                <h3 className="text-sm font-semibold">Exam Type Breakdown</h3>
                <ul className="mt-2 space-y-1 text-sm">
                    {(overview?.exam_type_breakdown ?? []).map((row) => (
                        <li key={row.type} className="flex justify-between">
                            <span>{row.type}</span>
                            <span>{row.total}</span>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}
