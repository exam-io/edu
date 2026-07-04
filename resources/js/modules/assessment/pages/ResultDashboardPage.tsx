import { useEffect, useMemo } from 'react';
import { useAssignmentStore } from '@modules/assignment/store/assignmentStore';

export function ResultDashboardPage() {
    const submissions = useAssignmentStore((state) => state.submissions);
    const loading = useAssignmentStore((state) => state.loading);
    const error = useAssignmentStore((state) => state.error);
    const fetchSubmissions = useAssignmentStore((state) => state.fetchSubmissions);

    useEffect(() => {
        void fetchSubmissions();
    }, [fetchSubmissions]);

    const scored = useMemo(() => submissions.filter((submission) => typeof submission.score === 'number'), [submissions]);
    const averageScore = useMemo(() => {
        if (scored.length === 0) {
            return 0;
        }

        const total = scored.reduce((sum, item) => sum + (item.score ?? 0), 0);
        return Number((total / scored.length).toFixed(2));
    }, [scored]);

    const passRate = useMemo(() => {
        if (scored.length === 0) {
            return 0;
        }

        const passed = scored.filter((submission) => (submission.score ?? 0) >= 50).length;
        return Number(((passed / scored.length) * 100).toFixed(2));
    }, [scored]);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Result Dashboard</h2>
            {loading ? <p className="text-sm">Loading metrics...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3 md:grid-cols-3">
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Average Score</p>
                    <p className="mt-1 text-2xl font-semibold">{averageScore}</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Pass Rate</p>
                    <p className="mt-1 text-2xl font-semibold">{passRate}%</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Submissions</p>
                    <p className="mt-1 text-2xl font-semibold">{submissions.length}</p>
                </article>
            </div>
        </section>
    );
}
