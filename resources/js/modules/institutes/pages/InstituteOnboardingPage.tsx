import { useMemo, useState } from 'react';
import { useInstitute } from '@modules/institutes/hooks/useInstitute';
import { OnboardingWizard } from '@modules/institutes/components/OnboardingWizard';

export function InstituteOnboardingPage() {
    const {
        institute,
        onboarding,
        academicSessions,
        loading,
        error,
        registerInstitute,
        updateBranding,
        createAcademicSession,
    } = useInstitute();

    const [name, setName] = useState('');
    const [code, setCode] = useState('');
    const [primaryColor, setPrimaryColor] = useState('#0b6eff');
    const [secondaryColor, setSecondaryColor] = useState('#00a889');

    const hasInstitute = institute !== null;

    const currentSession = useMemo(
        () => academicSessions.find((session) => session.is_current),
        [academicSessions]
    );

    return (
        <div className="mx-auto max-w-5xl space-y-6 p-6">
            <header className="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-900 to-slate-700 p-6 text-white">
                <h1 className="text-2xl font-semibold">Institute Management</h1>
                <p className="mt-2 text-sm text-slate-200">
                    Step 4 onboarding foundation with provisioning and academic sessions.
                </p>
            </header>

            {error ? (
                <div className="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{error}</div>
            ) : null}

            {!hasInstitute ? (
                <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 className="text-base font-semibold text-slate-900">Register Institute</h2>
                    <div className="mt-4 grid gap-3 md:grid-cols-2">
                        <input
                            className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            placeholder="Institute name"
                            value={name}
                            onChange={(event) => setName(event.target.value)}
                        />
                        <input
                            className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            placeholder="Institute code"
                            value={code}
                            onChange={(event) => setCode(event.target.value)}
                        />
                    </div>
                    <button
                        type="button"
                        className="mt-4 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white"
                        onClick={() => {
                            void registerInstitute({ name, code });
                        }}
                        disabled={loading || !name || !code}
                    >
                        Register
                    </button>
                </section>
            ) : null}

            {hasInstitute ? (
                <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 className="text-base font-semibold text-slate-900">Institute Profile</h2>
                    <p className="mt-2 text-sm text-slate-600">
                        {institute?.name} ({institute?.code})
                    </p>
                    <p className="text-xs uppercase tracking-wide text-slate-500">Status: {institute?.status}</p>

                    <div className="mt-4 flex flex-wrap items-end gap-3">
                        <div>
                            <label className="mb-1 block text-xs font-medium text-slate-600">Primary</label>
                            <input
                                type="color"
                                value={primaryColor}
                                onChange={(event) => setPrimaryColor(event.target.value)}
                                className="h-10 w-14 rounded border border-slate-300"
                            />
                        </div>
                        <div>
                            <label className="mb-1 block text-xs font-medium text-slate-600">Secondary</label>
                            <input
                                type="color"
                                value={secondaryColor}
                                onChange={(event) => setSecondaryColor(event.target.value)}
                                className="h-10 w-14 rounded border border-slate-300"
                            />
                        </div>
                        <button
                            type="button"
                            className="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white"
                            onClick={() => {
                                if (!institute) {
                                    return;
                                }
                                void updateBranding({
                                    primary_color: primaryColor,
                                    secondary_color: secondaryColor,
                                });
                            }}
                        >
                            Update Branding
                        </button>
                        <button
                            type="button"
                            className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700"
                            onClick={() => {
                                if (!institute) {
                                    return;
                                }
                                const nowYear = new Date().getFullYear();
                                void createAcademicSession({
                                    name: `${nowYear}-${nowYear + 1}`,
                                    code: `AY-${nowYear}`,
                                    starts_on: `${nowYear}-01-01`,
                                    ends_on: `${nowYear}-12-31`,
                                    is_current: true,
                                    status: 'active',
                                });
                            }}
                        >
                            Create Session
                        </button>
                    </div>

                    {currentSession ? (
                        <p className="mt-4 text-sm text-slate-600">
                            Current session: <span className="font-medium">{currentSession.name}</span>
                        </p>
                    ) : null}
                </section>
            ) : null}

            <OnboardingWizard wizard={onboarding} />
        </div>
    );
}
