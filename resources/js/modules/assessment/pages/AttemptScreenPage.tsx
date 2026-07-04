import { useEffect, useMemo, useState } from 'react';
import { useAttemptStore } from '@modules/assessment/store/attemptStore';

export function AttemptScreenPage() {
    const currentAttempt = useAttemptStore((state) => state.currentAttempt);
    const loading = useAttemptStore((state) => state.loading);
    const error = useAttemptStore((state) => state.error);
    const timer = useAttemptStore((state) => state.timer);
    const tick = useAttemptStore((state) => state.tick);
    const saveAnswer = useAttemptStore((state) => state.saveAnswer);
    const [markForReview, setMarkForReview] = useState(false);

    const firstQuestion = useMemo(() => currentAttempt?.assessment?.questions?.[0] ?? null, [currentAttempt]);

    useEffect(() => {
        const id = window.setInterval(() => tick(), 1000);
        return () => window.clearInterval(id);
    }, [tick]);

    async function handleSaveForReview(): Promise<void> {
        if (!currentAttempt || !firstQuestion) {
            return;
        }

        await saveAnswer(currentAttempt.assessment_id, firstQuestion.question_id, [], markForReview);
    }

    return (
        <section className="space-y-3">
            <h2 className="text-xl font-semibold">Attempt Screen</h2>
            <p className="text-sm text-[var(--color-muted)]">Timer: {timer}s</p>
            {firstQuestion ? (
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Question 1</p>
                    <p className="mt-1 text-sm font-medium">{firstQuestion.question?.stem ?? 'No question stem available.'}</p>
                    <label className="mt-3 flex items-center gap-2 text-sm">
                        <input type="checkbox" checked={markForReview} onChange={(e) => setMarkForReview(e.target.checked)} />
                        Mark for review
                    </label>
                    <button
                        type="button"
                        disabled={loading}
                        className="mt-3 rounded bg-[var(--color-primary)] px-3 py-2 text-sm text-white disabled:opacity-60"
                        onClick={() => void handleSaveForReview()}
                    >
                        {loading ? 'Saving...' : 'Save Answer'}
                    </button>
                </article>
            ) : (
                <p className="text-sm text-[var(--color-muted)]">Start an attempt from My Assessments to load randomized questions.</p>
            )}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
