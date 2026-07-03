import { useLocaleStore } from '@stores/localeStore';
import { useThemeStore } from '@stores/themeStore';

export function DashboardHome() {
    const { language, setLanguage } = useLocaleStore();
    const { theme, setTheme } = useThemeStore();

    return (
        <main className="mx-auto flex min-h-screen w-full max-w-5xl flex-col items-start justify-center gap-8 px-6 py-10">
            <section className="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-8 shadow-lg">
                <p className="text-sm uppercase tracking-[0.2em] text-[var(--color-muted)]">EduOS</p>
                <h1 className="mt-3 text-4xl font-semibold leading-tight">Multi-tenant Education SaaS Foundation</h1>
                <p className="mt-4 max-w-2xl text-[var(--color-muted)]">
                    Step 1 baseline is ready with modular monolith architecture, tenant context abstractions,
                    and frontend theming plus localization primitives.
                </p>

                <div className="mt-8 flex flex-wrap gap-3">
                    <button
                        type="button"
                        className="rounded-lg bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white"
                        onClick={() => setTheme(theme === 'light' ? 'dark' : 'light')}
                    >
                        Theme: {theme}
                    </button>
                    <button
                        type="button"
                        className="rounded-lg border border-[var(--color-border)] px-4 py-2 text-sm font-medium"
                        onClick={() => setLanguage(language === 'en' ? 'hi' : 'en')}
                    >
                        Language: {language.toUpperCase()}
                    </button>
                </div>
            </section>
        </main>
    );
}
