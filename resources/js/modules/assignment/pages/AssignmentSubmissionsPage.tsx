import { useAssignmentSubmissions } from '@modules/assignment/hooks/useAssignmentSubmissions';

export function AssignmentSubmissionsPage() {
    const { submissions, loading, error } = useAssignmentSubmissions();

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Assignment Submissions</h2>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {submissions.map((submission) => (
                    <article key={submission.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="font-medium">Submission #{submission.id}</p>
                        <p className="text-xs text-[var(--color-muted)]">Assessment: {submission.assessment_id} | Status: {submission.status}</p>
                        <p className="mt-1 text-xs">{submission.file_path}</p>
                    </article>
                ))}
            </div>
        </section>
    );
}
