import { useSettings } from '@modules/settings/hooks/useSettings';
import { FormEvent, useEffect, useState } from 'react';

type Theme = 'light' | 'dark';
type Language = 'en' | 'hi';

export function SettingsPage() {
    const { setting, loading, error, initialize, save } = useSettings();
    const [language, setLanguage] = useState<Language>('en');
    const [theme, setTheme] = useState<Theme>('light');
    const [timezone, setTimezone] = useState('UTC');
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState<string | null>(null);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    useEffect(() => {
        if (!setting) {
            return;
        }

        setLanguage(setting.language);
        setTheme(setting.theme);
        setTimezone(setting.timezone);
    }, [setting]);

    async function handleSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        setSaving(true);
        setMessage(null);

        try {
            await save({ language, theme, timezone });
            setMessage('Settings updated successfully.');
        } catch (submitError) {
            setMessage(submitError instanceof Error ? submitError.message : 'Unable to update settings.');
        } finally {
            setSaving(false);
        }
    }

    if (loading && setting === null) {
        return <div className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">Loading settings...</div>;
    }

    return (
        <section className="space-y-4 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-sm">
            <header>
                <h2 className="text-lg font-semibold">User Settings</h2>
                <p className="text-sm text-[var(--color-muted)]">Manage your language, theme, and timezone preferences.</p>
            </header>

            {error ? <div className="rounded-md bg-red-50 px-3 py-2 text-sm text-red-700">{error}</div> : null}
            {message ? <div className="rounded-md bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{message}</div> : null}

            <form className="grid gap-4 md:grid-cols-3" onSubmit={handleSubmit}>
                <label className="grid gap-1 text-sm">
                    <span className="font-medium">Language</span>
                    <select
                        value={language}
                        onChange={(event) => setLanguage(event.target.value as Language)}
                        className="rounded-md border border-[var(--color-border)] bg-white px-3 py-2"
                    >
                        <option value="en">English</option>
                        <option value="hi">Hindi</option>
                    </select>
                </label>

                <label className="grid gap-1 text-sm">
                    <span className="font-medium">Theme</span>
                    <select
                        value={theme}
                        onChange={(event) => setTheme(event.target.value as Theme)}
                        className="rounded-md border border-[var(--color-border)] bg-white px-3 py-2"
                    >
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                    </select>
                </label>

                <label className="grid gap-1 text-sm">
                    <span className="font-medium">Timezone</span>
                    <input
                        value={timezone}
                        onChange={(event) => setTimezone(event.target.value)}
                        className="rounded-md border border-[var(--color-border)] bg-white px-3 py-2"
                        placeholder="UTC"
                    />
                </label>

                <div className="md:col-span-3">
                    <button
                        type="submit"
                        disabled={saving}
                        className="rounded-md bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white disabled:opacity-60"
                    >
                        {saving ? 'Saving...' : 'Save Settings'}
                    </button>
                </div>
            </form>
        </section>
    );
}
