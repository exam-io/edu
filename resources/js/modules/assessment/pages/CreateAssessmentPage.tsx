import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { assessmentService } from '@modules/assessment/services/assessmentService';

export function CreateAssessmentPage() {
    const navigate = useNavigate();
    const [title, setTitle] = useState('');
    const [type, setType] = useState('Quiz');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    async function submit(): Promise<void> {
        setLoading(true);
        setError(null);

        try {
            await assessmentService.createAssessment({
                title,
                type,
                total_marks: 100,
                passing_marks: 40,
                status: 'draft',
            });
            navigate('/assessments');
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to create assessment.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <section className="max-w-2xl space-y-4 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h2 className="text-xl font-semibold">Create Assessment</h2>
            <label className="block">
                <span className="text-sm">Title</span>
                <input className="mt-1 w-full rounded border border-[var(--color-border)] px-3 py-2" value={title} onChange={(e) => setTitle(e.target.value)} />
            </label>
            <label className="block">
                <span className="text-sm">Type</span>
                <select className="mt-1 w-full rounded border border-[var(--color-border)] px-3 py-2" value={type} onChange={(e) => setType(e.target.value)}>
                    {['Assignment', 'Quiz', 'Practice Test', 'Mock Test', 'Unit Test', 'Mid-Term', 'Final Exam', 'Entrance Exam', 'Homework'].map((item) => (
                        <option key={item} value={item}>{item}</option>
                    ))}
                </select>
            </label>
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <button type="button" disabled={loading} onClick={() => void submit()} className="rounded bg-[var(--color-primary)] px-3 py-2 text-sm text-white disabled:opacity-60">
                {loading ? 'Creating...' : 'Create'}
            </button>
        </section>
    );
}
