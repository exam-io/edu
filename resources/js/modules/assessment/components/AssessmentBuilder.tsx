import { useMemo } from 'react';
import { useAssessmentStore } from '@modules/assessment/store/assessmentStore';

export function AssessmentBuilder() {
    const selected = useAssessmentStore((state) => state.selectedAssessment);

    const count = useMemo(() => selected?.questions?.length ?? 0, [selected]);

    if (!selected) {
        return <p className="text-sm text-[var(--color-muted)]">Select an assessment to open the builder.</p>;
    }

    return (
        <section className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h3 className="text-lg font-semibold">Assessment Builder</h3>
            <p className="mt-2 text-sm text-[var(--color-muted)]">{selected.title}</p>
            <p className="mt-1 text-xs text-[var(--color-muted)]">Questions: {count}</p>
            <div className="mt-4 grid gap-2 text-sm md:grid-cols-3">
                <div className="rounded border border-[var(--color-border)] p-2">Manual question selection</div>
                <div className="rounded border border-[var(--color-border)] p-2">Question bank selection</div>
                <div className="rounded border border-[var(--color-border)] p-2">AI-generated question selection</div>
            </div>
        </section>
    );
}
