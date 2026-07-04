interface ToggleProps {
    label: string;
    checked: boolean;
    onChange: (checked: boolean) => void;
}

export function Toggle({ label, checked, onChange }: ToggleProps) {
    return (
        <label className="flex items-center justify-between gap-3 rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm">
            <span>{label}</span>
            <button
                type="button"
                role="switch"
                aria-checked={checked}
                onClick={() => onChange(!checked)}
                className={`relative inline-flex h-6 w-11 items-center rounded-full transition ${
                    checked ? 'bg-[var(--color-primary)]' : 'bg-[var(--color-border)]'
                }`}
            >
                <span
                    className={`inline-block h-5 w-5 transform rounded-full bg-white transition ${
                        checked ? 'translate-x-5' : 'translate-x-1'
                    }`}
                />
            </button>
        </label>
    );
}
