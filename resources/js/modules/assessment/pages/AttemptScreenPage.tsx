import { Clock3, Save, TimerReset } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import { Checkbox } from '@components/forms/Checkbox';
import { EmptyState } from '@components/states/EmptyState';
import { Textarea } from '@components/forms/Textarea';
import { TextInput } from '@components/forms/TextInput';
import { useAttemptStore } from '@modules/assessment/store/attemptStore';

export function AttemptScreenPage() {
    const currentAttempt = useAttemptStore((state) => state.currentAttempt);
    const loading = useAttemptStore((state) => state.loading);
    const error = useAttemptStore((state) => state.error);
    const timer = useAttemptStore((state) => state.timer);
    const tick = useAttemptStore((state) => state.tick);
    const saveAnswer = useAttemptStore((state) => state.saveAnswer);
    const startAttempt = useAttemptStore((state) => state.startAttempt);
    const [markForReview, setMarkForReview] = useState(false);
    const [assessmentId, setAssessmentId] = useState('');
    const [activeIndex, setActiveIndex] = useState(0);
    const [answerDraft, setAnswerDraft] = useState('');
    const [autosaveText, setAutosaveText] = useState('Not saved yet');

    const questions = useMemo(() => currentAttempt?.assessment?.questions ?? [], [currentAttempt]);
    const activeQuestion = useMemo(() => questions[activeIndex] ?? null, [questions, activeIndex]);
    const progress = questions.length === 0 ? 0 : Math.round(((activeIndex + 1) / questions.length) * 100);

    useEffect(() => {
        const id = window.setInterval(() => tick(), 1000);
        return () => window.clearInterval(id);
    }, [tick]);

    async function handleSaveForReview(): Promise<void> {
        if (!currentAttempt || !activeQuestion) {
            return;
        }

        await saveAnswer(currentAttempt.assessment_id, activeQuestion.question_id, [answerDraft], markForReview);
        setAutosaveText(`Autosaved at ${new Date().toLocaleTimeString()}`);
    }

    async function handleStartAttempt(): Promise<void> {
        const id = Number(assessmentId);

        if (!id || Number.isNaN(id)) {
            return;
        }

        await startAttempt(id);
        setActiveIndex(0);
        setAutosaveText('Attempt started');
    }

    return (
        <section className="space-y-4">
            <header className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                <div className="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 className="text-lg font-semibold">Exam Attempt</h2>
                        <p className="text-sm text-[var(--color-muted)]">Timer, progress tracking, mark-for-review, and autosave status.</p>
                    </div>
                    <div className="flex items-center gap-2 rounded-full border border-[var(--color-border)] px-3 py-1 text-sm font-medium">
                        <Clock3 size={14} />
                        {timer}s
                    </div>
                </div>

                <div className="mt-3 grid gap-3 md:grid-cols-[1fr_auto]">
                    <TextInput
                        label="Assessment ID"
                        value={assessmentId}
                        onChange={(event) => setAssessmentId(event.target.value)}
                        placeholder="Enter assessment id"
                    />
                    <button type="button" className="btn-primary self-end" onClick={() => void handleStartAttempt()}>
                        <TimerReset size={14} />
                        Start Attempt
                    </button>
                </div>
            </header>

            {activeQuestion ? (
                <div className="grid gap-4 xl:grid-cols-[1fr_280px]">
                    <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
                        <div className="mb-4">
                            <div className="mb-2 flex items-center justify-between text-xs text-[var(--color-muted)]">
                                <span>
                                    Question {activeIndex + 1} / {questions.length}
                                </span>
                                <span>{progress}% complete</span>
                            </div>
                            <div className="h-2 rounded-full bg-[var(--color-surface-alt)]">
                                <div className="h-2 rounded-full bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-primary)]" style={{ width: `${progress}%` }} />
                            </div>
                        </div>

                        <p className="text-base font-medium">{activeQuestion.question?.stem ?? 'No question stem available.'}</p>

                        <div className="mt-4 space-y-3">
                            <Textarea
                                label="Your Answer"
                                value={answerDraft}
                                onChange={(event) => setAnswerDraft(event.target.value)}
                                placeholder="Type your answer here"
                            />
                            <Checkbox label="Mark for review" checked={markForReview} onChange={setMarkForReview} />
                        </div>

                        <div className="mt-4 flex flex-wrap items-center gap-2">
                            <button type="button" className="btn-primary" disabled={loading} onClick={() => void handleSaveForReview()}>
                                <Save size={14} />
                                {loading ? 'Saving...' : 'Save Answer'}
                            </button>
                            <button
                                type="button"
                                className="btn-ghost"
                                onClick={() => {
                                    setActiveIndex((index) => Math.max(0, index - 1));
                                    setAnswerDraft('');
                                }}
                                disabled={activeIndex === 0}
                            >
                                Previous
                            </button>
                            <button
                                type="button"
                                className="btn-ghost"
                                onClick={() => {
                                    setActiveIndex((index) => Math.min(questions.length - 1, index + 1));
                                    setAnswerDraft('');
                                }}
                                disabled={activeIndex >= questions.length - 1}
                            >
                                Next
                            </button>
                            <span className="text-xs text-[var(--color-muted)]">{autosaveText}</span>
                        </div>
                    </article>

                    <aside className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                        <h3 className="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--color-muted)]">Question Navigator</h3>
                        <div className="mt-3 grid grid-cols-5 gap-2">
                            {questions.map((question, index) => (
                                <button
                                    key={question.id}
                                    type="button"
                                    onClick={() => {
                                        setActiveIndex(index);
                                        setAnswerDraft('');
                                    }}
                                    className={`rounded-lg border px-2 py-1 text-xs ${
                                        index === activeIndex
                                            ? 'border-[var(--color-primary)] bg-[color-mix(in_oklab,var(--color-primary)_14%,transparent)]'
                                            : 'border-[var(--color-border)] bg-[var(--color-surface-alt)]'
                                    }`}
                                >
                                    {index + 1}
                                </button>
                            ))}
                        </div>
                    </aside>
                </div>
            ) : (
                <EmptyState
                    title="No active attempt"
                    description="Enter an assessment ID and start an attempt to load question navigator and timed mode."
                    primaryAction={<button type="button" className="btn-primary" onClick={() => void handleStartAttempt()}>Start Attempt</button>}
                />
            )}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}
