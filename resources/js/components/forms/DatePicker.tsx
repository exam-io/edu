import type { InputHTMLAttributes } from 'react';

interface DatePickerProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'className'> {
    label: string;
}

export function DatePicker({ label, id, ...props }: DatePickerProps) {
    const fieldId = id ?? label.toLowerCase().replace(/\s+/g, '-');

    return (
        <label className="grid gap-1 text-sm" htmlFor={fieldId}>
            <span className="font-medium">{label}</span>
            <input
                id={fieldId}
                type="date"
                {...props}
                className="w-full rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm outline-none transition focus:border-[var(--color-primary)]"
            />
        </label>
    );
}
