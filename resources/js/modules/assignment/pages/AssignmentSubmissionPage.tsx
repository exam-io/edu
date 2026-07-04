import { useState } from 'react';
import { assignmentService } from '@modules/assignment/services/assignmentService';

export function AssignmentSubmissionPage() {
    const [assessmentId, setAssessmentId] = useState('');
    const [filePath, setFilePath] = useState('');
    const [message, setMessage] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    async function submit(): Promise<void> {
        setMessage(null);
        setError(null);

        try {
            await assignmentService.submit(Number(assessmentId), filePath);
            setMessage('Assignment submitted successfully.');
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to submit assignment.');
        }
    }

    return (
        <section className="max-w-xl space-y-4 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h2 className="text-xl font-semibold">Assignment Submission</h2>
            <label className="block">
                <span className="text-sm">Assessment ID</span>
                <input className="mt-1 w-full rounded border border-[var(--color-border)] px-3 py-2" value={assessmentId} onChange={(e) => setAssessmentId(e.target.value)} />
            </label>
            <label className="block">
                <span className="text-sm">File Path</span>
                <input className="mt-1 w-full rounded border border-[var(--color-border)] px-3 py-2" value={filePath} onChange={(e) => setFilePath(e.target.value)} />
            </label>
            {message ? <p className="text-sm text-emerald-600">{message}</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <button type="button" className="rounded bg-[var(--color-primary)] px-3 py-2 text-sm text-white" onClick={() => void submit()}>
                Submit
            </button>
        </section>
    );
}
