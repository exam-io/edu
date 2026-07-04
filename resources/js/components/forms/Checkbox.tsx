interface CheckboxProps {
    label: string;
    checked: boolean;
    onChange: (checked: boolean) => void;
}

export function Checkbox({ label, checked, onChange }: CheckboxProps) {
    return (
        <label className="inline-flex items-center gap-2 text-sm">
            <input
                type="checkbox"
                checked={checked}
                onChange={(event) => onChange(event.target.checked)}
                className="h-4 w-4 rounded border-[var(--color-border)] text-[var(--color-primary)]"
            />
            <span>{label}</span>
        </label>
    );
}
