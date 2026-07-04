import { Link } from 'react-router-dom';
import { useAssessments } from '@modules/assessment/hooks/useAssessments';
import { useAssessmentStore } from '@modules/assessment/store/assessmentStore';

export function AssessmentListPage() {
    const { assessments, loading, error } = useAssessments();
    const selectAssessment = useAssessmentStore((state) => state.selectAssessment);

    return (
        <section className="space-y-4">
            <div className="flex items-center justify-between">
                <h2 className="text-xl font-semibold">Assessment List</h2>
                <Link to="/assessments/create" className="rounded bg-[var(--color-primary)] px-3 py-2 text-sm text-white">
                    Create Assessment
                </Link>
            </div>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {assessments.map((assessment) => (
                    <button
                        key={assessment.id}
                        type="button"
                        className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4 text-left"
                        onClick={() => selectAssessment(assessment)}
                    >
                        <p className="font-medium">{assessment.title}</p>
                        <p className="text-xs text-[var(--color-muted)]">{assessment.type} | {assessment.status}</p>
                    </button>
                ))}
            </div>
        </section>
    );
}
