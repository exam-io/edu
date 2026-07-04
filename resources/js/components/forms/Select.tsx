import type { SelectHTMLAttributes } from 'react';

interface Option {
    label: string;
    value: string;
}

interface SelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'className'> {
    label: string;
    options: Option[];
    hint?: string;
    error?: string;
}

export function Select({ label, options, hint, error, id, ...props }: SelectProps) {
    const fieldId = id ?? label.toLowerCase().replace(/\s+/g, '-');

    return (
        <label className="grid gap-1 text-sm" htmlFor={fieldId}>
            <span className="font-medium text-[var(--color-text)]">{label}</span>
            <select
                id={fieldId}
                {...props}
                className="w-full rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm outline-none transition focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[color-mix(in_oklab,var(--color-primary)_22%,transparent)]"
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
            {error ? <span className="text-xs text-rose-600 dark:text-rose-400">{error}</span> : null}
            {!error && hint ? <span className="text-xs text-[var(--color-muted)]">{hint}</span> : null}
        </label>
    );
}
