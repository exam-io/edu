import { useState } from 'react';
import { assessmentService } from '@modules/assessment/services/assessmentService';

export function PublishAssessmentPage() {
    const [assessmentId, setAssessmentId] = useState('');
    const [message, setMessage] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    async function publish(): Promise<void> {
        setError(null);
        setMessage(null);

        try {
            await assessmentService.publishAssessment(Number(assessmentId));
            setMessage('Assessment published successfully.');
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to publish assessment.');
        }
    }

    return (
        <section className="max-w-xl space-y-4 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h2 className="text-xl font-semibold">Publish Assessment</h2>
            <label className="block">
                <span className="text-sm">Assessment ID</span>
                <input
                    className="mt-1 w-full rounded border border-[var(--color-border)] px-3 py-2"
                    value={assessmentId}
                    onChange={(e) => setAssessmentId(e.target.value)}
                />
            </label>
            {message ? <p className="text-sm text-emerald-600">{message}</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <button type="button" className="rounded bg-[var(--color-primary)] px-3 py-2 text-sm text-white" onClick={() => void publish()}>
                Publish
            </button>
        </section>
    );
}
