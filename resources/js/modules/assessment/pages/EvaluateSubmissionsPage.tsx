import { useEffect, useMemo, useState } from 'react';
import { useAssignmentStore } from '@modules/assignment/store/assignmentStore';

export function EvaluateSubmissionsPage() {
    const submissions = useAssignmentStore((state) => state.submissions);
    const loading = useAssignmentStore((state) => state.loading);
    const error = useAssignmentStore((state) => state.error);
    const fetchSubmissions = useAssignmentStore((state) => state.fetchSubmissions);
    const [statusFilter, setStatusFilter] = useState<'all' | 'submitted' | 'graded'>('all');

    useEffect(() => {
        void fetchSubmissions();
    }, [fetchSubmissions]);

    const filtered = useMemo(() => {
        if (statusFilter === 'all') {
            return submissions;
        }

        return submissions.filter((submission) => submission.status === statusFilter);
    }, [statusFilter, submissions]);

    return (
        <section className="space-y-3">
            <h2 className="text-xl font-semibold">Evaluate Submissions</h2>
            <div className="flex items-center gap-2">
                <label className="text-sm text-[var(--color-muted)]" htmlFor="status-filter">Status:</label>
                <select
                    id="status-filter"
                    className="rounded border border-[var(--color-border)] bg-[var(--color-surface)] px-2 py-1 text-sm"
                    value={statusFilter}
                    onChange={(e) => setStatusFilter(e.target.value as 'all' | 'submitted' | 'graded')}
                >
                    <option value="all">All</option>
                    <option value="submitted">Submitted</option>
                    <option value="graded">Graded</option>
                </select>
            </div>
            {loading ? <p className="text-sm">Loading submissions...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {filtered.map((submission) => (
                    <article key={submission.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="text-sm font-medium">Submission #{submission.id}</p>
                        <p className="text-xs text-[var(--color-muted)]">Assessment: {submission.assessment_id}</p>
                        <p className="text-xs text-[var(--color-muted)]">Status: {submission.status}</p>
                        <p className="text-xs text-[var(--color-muted)]">Score: {submission.score ?? 'Pending'}</p>
                    </article>
                ))}
            </div>
        </section>
    );
}
