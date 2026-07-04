import { useAssessments } from '@modules/assessment/hooks/useAssessments';

export function MyAssessmentsPage() {
    const { assessments, loading } = useAssessments();

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">My Assessments</h2>
            {loading ? <p className="text-sm">Loading...</p> : null}
            <div className="grid gap-3">
                {assessments.map((assessment) => (
                    <article key={assessment.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="font-medium">{assessment.title}</p>
                        <p className="text-xs text-[var(--color-muted)]">{assessment.type} | {assessment.status}</p>
                    </article>
                ))}
            </div>
        </section>
    );
}
