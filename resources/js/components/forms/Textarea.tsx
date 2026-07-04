import type { TextareaHTMLAttributes } from 'react';

interface TextareaProps extends Omit<TextareaHTMLAttributes<HTMLTextAreaElement>, 'className'> {
    label: string;
    hint?: string;
    error?: string;
}

export function Textarea({ label, hint, error, id, rows = 4, ...props }: TextareaProps) {
    const fieldId = id ?? label.toLowerCase().replace(/\s+/g, '-');

    return (
        <label className="grid gap-1 text-sm" htmlFor={fieldId}>
            <span className="font-medium text-[var(--color-text)]">{label}</span>
            <textarea
                id={fieldId}
                rows={rows}
                {...props}
                className="w-full rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm outline-none transition focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[color-mix(in_oklab,var(--color-primary)_22%,transparent)]"
            />
            {error ? <span className="text-xs text-rose-600 dark:text-rose-400">{error}</span> : null}
            {!error && hint ? <span className="text-xs text-[var(--color-muted)]">{hint}</span> : null}
        </label>
    );
}
