import { useSettings } from '@modules/settings/hooks/useSettings';
import { FormEvent, useEffect, useState } from 'react';
import { FormStepIndicator } from '@components/forms/FormStepIndicator';
import { Select } from '@components/forms/Select';
import { TextInput } from '@components/forms/TextInput';
import { Toggle } from '@components/forms/Toggle';
import { useDraftAutosave } from '@hooks/useDraftAutosave';

type Theme = 'light' | 'dark';
type Language = 'en' | 'hi';

export function SettingsPage() {
    const { setting, loading, error, initialize, save } = useSettings();
    const [draft, setDraft] = useState<Partial<{ language: Language; theme: Theme; timezone: string }>>({});
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState<string | null>(null);
    const [autoJoinLiveClasses, setAutoJoinLiveClasses] = useState(false);
    const [showDesktopNotifications, setShowDesktopNotifications] = useState(true);

    const language: Language = draft.language ?? setting?.language ?? 'en';
    const theme: Theme = draft.theme ?? setting?.theme ?? 'light';
    const timezone = draft.timezone ?? setting?.timezone ?? 'UTC';
    const { lastSavedAt } = useDraftAutosave('settings', { language, theme, timezone, autoJoinLiveClasses, showDesktopNotifications });

    useEffect(() => {
        void initialize();
    }, [initialize]);

    async function handleSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        setSaving(true);
        setMessage(null);

        try {
            await save({ language, theme, timezone });
            setDraft({});
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
        <section className="space-y-4 rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <header>
                <h2 className="text-xl font-semibold">User Settings</h2>
                <p className="text-sm text-[var(--color-muted)]">Manage theme, language, and experience preferences with autosave indicators.</p>
            </header>

            <FormStepIndicator steps={['General', 'Appearance', 'Notifications']} current={0} />

            {error ? <div className="rounded-md bg-red-50 px-3 py-2 text-sm text-red-700">{error}</div> : null}
            {message ? <div className="rounded-md bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{message}</div> : null}

            <form className="grid gap-4 lg:grid-cols-2" onSubmit={handleSubmit}>
                <Select
                    label="Language"
                    value={language}
                    onChange={(event) => setDraft((current) => ({ ...current, language: event.target.value as Language }))}
                    options={[
                        { label: 'English', value: 'en' },
                        { label: 'Hindi', value: 'hi' },
                    ]}
                />

                <Select
                    label="Theme"
                    value={theme}
                    onChange={(event) => setDraft((current) => ({ ...current, theme: event.target.value as Theme }))}
                    options={[
                        { label: 'Light', value: 'light' },
                        { label: 'Dark', value: 'dark' },
                    ]}
                />

                <TextInput
                    label="Timezone"
                    value={timezone}
                    onChange={(event) => setDraft((current) => ({ ...current, timezone: event.target.value }))}
                    placeholder="UTC"
                />

                <div className="space-y-2">
                    <Toggle label="Auto join live classes" checked={autoJoinLiveClasses} onChange={setAutoJoinLiveClasses} />
                    <Toggle label="Desktop notifications" checked={showDesktopNotifications} onChange={setShowDesktopNotifications} />
                </div>

                <div className="lg:col-span-2 flex items-center gap-3">
                    <button
                        type="submit"
                        disabled={saving}
                        className="btn-primary disabled:opacity-60"
                    >
                        {saving ? 'Saving...' : 'Save Settings'}
                    </button>
                    <span className="text-xs text-[var(--color-muted)]">
                        Draft {lastSavedAt ? `autosaved at ${lastSavedAt.toLocaleTimeString()}` : 'autosaves while editing'}
                    </span>
                </div>
            </form>
        </section>
    );
}
