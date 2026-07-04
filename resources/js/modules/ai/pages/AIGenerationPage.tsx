import { Bot, BookOpen, FileText, ListChecks, Sparkles, Trash2 } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import { AIUploadCard } from '@components/forms/AIUploadCard';
import { FileDropzone } from '@components/forms/FileDropzone';
import { MultiSelect } from '@components/forms/MultiSelect';
import { RichTextEditor } from '@components/forms/RichTextEditor';
import { Select } from '@components/forms/Select';
import { Textarea } from '@components/forms/Textarea';
import { TextInput } from '@components/forms/TextInput';
import { EmptyState } from '@components/states/EmptyState';
import { SkeletonLoader } from '@components/states/SkeletonLoader';
import { useAIStore } from '@modules/ai/store/aiStore';

export function AIGenerationPage() {
    const { requests, loading, error, initialize, refresh, createRequest, deleteRequest } = useAIStore();
    const [title, setTitle] = useState('');
    const [prompt, setPrompt] = useState('Generate a concise and student-friendly summary.');
    const [type, setType] = useState<'questions' | 'notes' | 'summary'>('summary');
    const [options, setOptions] = useState<string[]>(['summary']);
    const [sourceText, setSourceText] = useState('');

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const statusCounts = useMemo(
        () => ({
            queued: requests.filter((item) => item.status === 'queued').length,
            processing: requests.filter((item) => item.status === 'processing').length,
            completed: requests.filter((item) => item.status === 'completed').length,
            failed: requests.filter((item) => item.status === 'failed').length,
        }),
        [requests],
    );

    async function handleGenerate() {
        await createRequest({
            title,
            prompt_text: prompt,
            generation_type: type,
            source_text: sourceText,
            options,
        });
    }

    return (
        <section className="space-y-5">
            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                    <p className="text-xs uppercase tracking-[0.14em] text-[var(--color-muted)]">Queued</p>
                    <p className="mt-2 font-mono text-3xl font-semibold">{statusCounts.queued}</p>
                </article>
                <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                    <p className="text-xs uppercase tracking-[0.14em] text-[var(--color-muted)]">Processing</p>
                    <p className="mt-2 font-mono text-3xl font-semibold">{statusCounts.processing}</p>
                </article>
                <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                    <p className="text-xs uppercase tracking-[0.14em] text-[var(--color-muted)]">Completed</p>
                    <p className="mt-2 font-mono text-3xl font-semibold">{statusCounts.completed}</p>
                </article>
                <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                    <p className="text-xs uppercase tracking-[0.14em] text-[var(--color-muted)]">Failed</p>
                    <p className="mt-2 font-mono text-3xl font-semibold">{statusCounts.failed}</p>
                </article>
            </div>

            <div className="grid gap-4 xl:grid-cols-[1.2fr_1fr]">
                <section className="space-y-4 rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
                    <header>
                        <h2 className="text-lg font-semibold">AI Studio Workspace</h2>
                        <p className="text-sm text-[var(--color-muted)]">Upload content, define generation goals, and run premium AI workflows.</p>
                    </header>

                    <div className="grid gap-3 md:grid-cols-2">
                        <AIUploadCard
                            title="Upload PDF"
                            description="Add textbook chapters or modules."
                            icon={<FileText size={16} />}
                            dropzone={<FileDropzone title="Drop PDF" accept=".pdf" />}
                        />
                        <AIUploadCard
                            title="Upload Notes"
                            description="Import class notes and handouts."
                            icon={<BookOpen size={16} />}
                            dropzone={<FileDropzone title="Drop DOC/TXT" accept=".txt,.doc,.docx" />}
                        />
                    </div>

                    <div className="grid gap-3 md:grid-cols-2">
                        <TextInput label="Job Title" value={title} onChange={(event) => setTitle(event.target.value)} placeholder="Grade 9 Biology revision" />
                        <Select
                            label="Generation Type"
                            value={type}
                            onChange={(event) => setType(event.target.value as 'questions' | 'notes' | 'summary')}
                            options={[
                                { label: 'Questions', value: 'questions' },
                                { label: 'Notes', value: 'notes' },
                                { label: 'Summary', value: 'summary' },
                            ]}
                        />
                    </div>

                    <MultiSelect
                        label="Generation Options"
                        value={options}
                        onChange={setOptions}
                        options={[
                            { label: 'Questions', value: 'questions' },
                            { label: 'Notes', value: 'notes' },
                            { label: 'Summary', value: 'summary' },
                            { label: 'Flash Cards', value: 'flashcards' },
                            { label: 'Worksheet', value: 'worksheet' },
                        ]}
                    />

                    <Textarea
                        label="Prompt Input"
                        value={prompt}
                        onChange={(event) => setPrompt(event.target.value)}
                        hint="Be specific about level, tone, and output format."
                    />

                    <RichTextEditor label="Paste Text" value={sourceText} onChange={setSourceText} />

                    <div className="flex items-center gap-2">
                        <button type="button" className="btn-primary" onClick={() => void handleGenerate()} disabled={loading}>
                            <Sparkles size={16} />
                            {loading ? 'Generating...' : 'Generate Content'}
                        </button>
                        <p className="text-xs text-[var(--color-muted)]">Jobs are tracked in generation history with status cards.</p>
                    </div>
                </section>

                <section className="space-y-3">
                    <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                        <h3 className="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--color-muted)]">AI Suggestions</h3>
                        <div className="mt-3 space-y-2 text-sm">
                            <p className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">Generate 20 MCQs from latest physics notes.</p>
                            <p className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">Create worksheet differentiated by difficulty.</p>
                            <p className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">Summarize exam mistakes for remedial classes.</p>
                        </div>
                    </article>

                    <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                        <h3 className="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--color-muted)]">Job Status</h3>
                        <div className="mt-3 space-y-2">
                            {loading ? (
                                <>
                                    <SkeletonLoader className="h-12 w-full" />
                                    <SkeletonLoader className="h-12 w-full" />
                                </>
                            ) : (
                                requests.slice(0, 4).map((request) => (
                                    <article key={request.id} className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                                        <p className="text-sm font-medium">#{request.id} {request.generation_type}</p>
                                        <p className="mt-1 text-xs text-[var(--color-muted)]">Status: {request.status}</p>
                                    </article>
                                ))
                            )}
                        </div>
                    </article>
                </section>
            </div>

            <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                <header className="mb-3 flex items-center justify-between">
                    <h2 className="text-base font-semibold">Generation History</h2>
                    <button type="button" className="btn-ghost" onClick={() => void refresh()}>
                        <ListChecks size={14} /> Refresh
                    </button>
                </header>

                {error ? <p className="mb-3 rounded-lg bg-rose-500/10 px-3 py-2 text-sm text-rose-700 dark:text-rose-300">{error}</p> : null}

                {requests.length === 0 && !loading ? (
                    <EmptyState
                        title="No AI jobs yet"
                        description="Create your first generation request from the AI Studio panel above."
                        illustration={<Bot size={24} />}
                        primaryAction={<button type="button" className="btn-primary" onClick={() => void handleGenerate()}>Create First Job</button>}
                    />
                ) : (
                    <div className="grid gap-3 md:grid-cols-2">
                        {requests.map((request) => (
                            <article key={request.id} className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-4">
                                <div className="flex items-start justify-between gap-2">
                                    <div>
                                        <p className="text-sm font-semibold">{request.prompt_text ?? `Job #${request.id}`}</p>
                                        <p className="mt-1 text-xs text-[var(--color-muted)]">Type: {request.generation_type}</p>
                                    </div>
                                    <button type="button" className="btn-ghost" onClick={() => void deleteRequest(request.id)} aria-label="Delete request">
                                        <Trash2 size={14} />
                                    </button>
                                </div>
                                <p className="mt-2 text-xs">
                                    <span className="rounded-full border border-[var(--color-border)] px-2 py-1 capitalize">{request.status}</span>
                                </p>
                            </article>
                        ))}
                    </div>
                )}
            </section>
        </section>
    );
}
