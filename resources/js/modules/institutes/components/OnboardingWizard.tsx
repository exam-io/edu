import type { OnboardingWizard } from '@modules/institutes/types/institute';

interface OnboardingWizardProps {
    wizard: OnboardingWizard | null;
}

export function OnboardingWizard({ wizard }: OnboardingWizardProps) {
    if (!wizard) {
        return (
            <div className="rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-500">
                Onboarding is not available yet.
            </div>
        );
    }

    return (
        <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
                <h2 className="text-base font-semibold text-slate-900">Onboarding Wizard</h2>
                <span className="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Step: {wizard.current_step}
                </span>
            </div>

            <ol className="space-y-3">
                {wizard.steps.map((step) => (
                    <li key={step.key} className="flex items-center gap-3">
                        <span
                            className={`inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold ${
                                step.completed
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-slate-100 text-slate-500'
                            }`}
                        >
                            {step.completed ? 'OK' : '-'}
                        </span>
                        <span className="text-sm text-slate-700">{step.title}</span>
                    </li>
                ))}
            </ol>
        </section>
    );
}
