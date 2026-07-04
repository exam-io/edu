interface RadioOption {
    label: string;
    value: string;
}

interface RadioGroupProps {
    label: string;
    options: RadioOption[];
    value: string;
    onChange: (value: string) => void;
}

export function RadioGroup({ label, options, value, onChange }: RadioGroupProps) {
    return (
        <fieldset className="grid gap-2">
            <legend className="text-sm font-medium">{label}</legend>
            <div className="flex flex-wrap gap-2">
                {options.map((option) => (
                    <label key={option.value} className="inline-flex items-center gap-2 rounded-full border border-[var(--color-border)] px-3 py-1.5 text-xs">
                        <input
                            type="radio"
                            checked={value === option.value}
                            onChange={() => onChange(option.value)}
                        />
                        <span>{option.label}</span>
                    </label>
                ))}
            </div>
        </fieldset>
    );
}
