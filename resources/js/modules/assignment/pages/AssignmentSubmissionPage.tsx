import { CheckCircle2, Send } from 'lucide-react';
import { useMemo, useState } from 'react';
import { DatePicker } from '@components/forms/DatePicker';
import { FileDropzone } from '@components/forms/FileDropzone';
import { FormStepIndicator } from '@components/forms/FormStepIndicator';
import { TextInput } from '@components/forms/TextInput';
import { Textarea } from '@components/forms/Textarea';
import { useDraftAutosave } from '@hooks/useDraftAutosave';
import { assignmentService } from '@modules/assignment/services/assignmentService';

export function AssignmentSubmissionPage() {
    const [step, setStep] = useState(0);
    const [assessmentId, setAssessmentId] = useState('1024');
    const [filePath, setFilePath] = useState('student/submissions/math-worksheet.pdf');
    const [remarks, setRemarks] = useState('Attached worksheet includes rough calculations in final section.');
    const [dueDate, setDueDate] = useState('');
    const [message, setMessage] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    const steps = ['Assignment Details', 'Upload & Notes', 'Review & Submit'];
    const draft = useMemo(() => ({ assessmentId, filePath, remarks, dueDate }), [assessmentId, filePath, remarks, dueDate]);
    const { lastSavedAt, clearDraft } = useDraftAutosave('assignment-submit', draft);

    async function submit(): Promise<void> {
        setMessage(null);
        setError(null);

        try {
            await assignmentService.submit(Number(assessmentId), filePath);
            setMessage('Assignment submitted successfully.');
            clearDraft();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to submit assignment.');
        }
    }

    return (
        <section className="space-y-4 rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <header>
                <h2 className="text-xl font-semibold">Assignment Submission</h2>
                <p className="text-sm text-[var(--color-muted)]">Multi-step workflow with draft autosave and review state.</p>
            </header>

            <FormStepIndicator steps={steps} current={step} />

            {step === 0 ? (
                <div className="grid gap-3 md:grid-cols-2">
                    <TextInput
                        label="Assessment ID"
                        value={assessmentId}
                        onChange={(event) => setAssessmentId(event.target.value)}
                        hint="Use the published assessment identifier"
                    />
                    <DatePicker label="Due Date" value={dueDate} onChange={(event) => setDueDate(event.target.value)} />
                </div>
            ) : null}

            {step === 1 ? (
                <div className="space-y-3">
                    <FileDropzone title="Upload worksheet, project, or notes" accept=".pdf,.doc,.docx,.zip,.txt" />
                    <TextInput
                        label="File Path"
                        value={filePath}
                        onChange={(event) => setFilePath(event.target.value)}
                        hint="Current API expects file_path string"
                    />
                    <Textarea label="Remarks" value={remarks} onChange={(event) => setRemarks(event.target.value)} />
                </div>
            ) : null}

            {step === 2 ? (
                <article className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-4 text-sm">
                    <p><strong>Assessment:</strong> {assessmentId || '-'}</p>
                    <p className="mt-1"><strong>Due Date:</strong> {dueDate || 'Not set'}</p>
                    <p className="mt-1"><strong>File Path:</strong> {filePath || '-'}</p>
                    <p className="mt-1"><strong>Remarks:</strong> {remarks || '-'}</p>
                    <p className="mt-3 text-xs text-[var(--color-muted)]">
                        Draft autosaved {lastSavedAt ? `at ${lastSavedAt.toLocaleTimeString()}` : 'after any field changes'}.
                    </p>
                </article>
            ) : null}

            {message ? <p className="text-sm text-emerald-600">{message}</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <div className="flex flex-wrap items-center gap-2">
                <button type="button" className="btn-ghost" disabled={step === 0} onClick={() => setStep((value) => Math.max(0, value - 1))}>
                    Previous
                </button>
                {step < 2 ? (
                    <button type="button" className="btn-primary" onClick={() => setStep((value) => Math.min(2, value + 1))}>
                        Next
                    </button>
                ) : (
                    <button type="button" className="btn-primary" onClick={() => void submit()}>
                        <Send size={14} /> Submit Assignment
                    </button>
                )}
                {message ? <span className="inline-flex items-center gap-1 text-xs text-emerald-600"><CheckCircle2 size={14} /> Submitted</span> : null}
            </div>
        </section>
    );
}
