interface MultiSelectOption {
    label: string;
    value: string;
}

interface MultiSelectProps {
    label: string;
    options: MultiSelectOption[];
    value: string[];
    onChange: (value: string[]) => void;
}

export function MultiSelect({ label, options, value, onChange }: MultiSelectProps) {
    return (
        <fieldset className="grid gap-2">
            <legend className="text-sm font-medium text-[var(--color-text)]">{label}</legend>
            <div className="flex flex-wrap gap-2">
                {options.map((option) => {
                    const active = value.includes(option.value);

                    return (
                        <button
                            key={option.value}
                            type="button"
                            onClick={() => {
                                if (active) {
                                    onChange(value.filter((item) => item !== option.value));
                                    return;
                                }

                                onChange([...value, option.value]);
                            }}
                            className={`rounded-full border px-3 py-1 text-xs transition ${
                                active
                                    ? 'border-[var(--color-primary)] bg-[color-mix(in_oklab,var(--color-primary)_14%,transparent)] text-[var(--color-text)]'
                                    : 'border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-muted)] hover:border-[var(--color-primary)]'
                            }`}
                        >
                            {option.label}
                        </button>
                    );
                })}
            </div>
        </fieldset>
    );
}
