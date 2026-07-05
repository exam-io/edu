import { useState } from 'react';
import { reportingService } from '@modules/reporting/services/reportingService';

export function ReportBuilderPage() {
    const [name, setName] = useState('Enrollment Funnel');
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState<string | null>(null);

    async function createTemplate(): Promise<void> {
        setSaving(true);
        setMessage(null);
        try {
            await reportingService.createTemplate({
                name,
                slug: name.toLowerCase().replace(/\s+/g, '-'),
                source: 'analytics_metric_snapshots',
                definition: {
                    metric_key: 'events.count',
                    dimensions: ['event_name'],
                },
            });
            setMessage('Template created successfully.');
        } catch (error) {
            setMessage(error instanceof Error ? error.message : 'Failed to create template.');
        } finally {
            setSaving(false);
        }
    }

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Custom Report Builder</h2>
                <p className="text-sm text-[var(--color-muted)]">Define custom report templates from analytics datasets.</p>
            </header>
            <div className="rounded-md border border-[var(--color-border)] p-4">
                <label className="text-sm font-medium" htmlFor="reportName">Template Name</label>
                <input id="reportName" className="mt-2 w-full rounded border border-[var(--color-border)] px-3 py-2" value={name} onChange={(event) => setName(event.target.value)} />
                <button
                    type="button"
                    onClick={() => void createTemplate()}
                    className="mt-3 rounded bg-[var(--color-primary)] px-4 py-2 text-white"
                    disabled={saving}
                >
                    {saving ? 'Saving...' : 'Save Template'}
                </button>
                {message ? <p className="mt-2 text-sm">{message}</p> : null}
            </div>
        </section>
    );
}
