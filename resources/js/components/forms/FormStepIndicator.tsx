interface FormStepIndicatorProps {
    steps: string[];
    current: number;
}

export function FormStepIndicator({ steps, current }: FormStepIndicatorProps) {
    return (
        <ol className="grid gap-2 sm:grid-cols-3">
            {steps.map((step, index) => {
                const active = index === current;
                const done = index < current;

                return (
                    <li
                        key={step}
                        className={`rounded-[var(--radius-input)] border px-3 py-2 text-xs transition ${
                            active
                                ? 'border-[var(--color-primary)] bg-[color-mix(in_oklab,var(--color-primary)_12%,transparent)] text-[var(--color-text)]'
                                : done
                                  ? 'border-emerald-400/40 bg-emerald-400/10 text-emerald-700 dark:text-emerald-300'
                                  : 'border-[var(--color-border)] bg-[var(--color-surface-alt)] text-[var(--color-muted)]'
                        }`}
                    >
                        <span className="font-mono">{index + 1}.</span> {step}
                    </li>
                );
            })}
        </ol>
    );
}
