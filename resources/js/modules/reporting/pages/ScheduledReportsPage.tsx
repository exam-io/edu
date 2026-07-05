import { useState } from 'react';
import { reportingService } from '@modules/reporting/services/reportingService';

export function ScheduledReportsPage() {
    const [templateId, setTemplateId] = useState(1);
    const [message, setMessage] = useState<string | null>(null);

    async function scheduleDaily(): Promise<void> {
        setMessage(null);
        try {
            await reportingService.schedule({
                report_template_id: templateId,
                frequency: 'daily',
                next_run_at: new Date(Date.now() + 60 * 60 * 1000).toISOString(),
                filters: {
                    metric_key: 'events.count',
                },
            });
            setMessage('Scheduled report created.');
        } catch (error) {
            setMessage(error instanceof Error ? error.message : 'Failed to create schedule.');
        }
    }

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Scheduled Reports</h2>
                <p className="text-sm text-[var(--color-muted)]">Configure recurring report execution and exports.</p>
            </header>

            <div className="rounded-md border border-[var(--color-border)] p-4">
                <label className="text-sm font-medium" htmlFor="templateId">Template ID</label>
                <input
                    id="templateId"
                    className="mt-2 w-full rounded border border-[var(--color-border)] px-3 py-2"
                    type="number"
                    value={templateId}
                    onChange={(event) => setTemplateId(Number(event.target.value))}
                />
                <button type="button" onClick={() => void scheduleDaily()} className="mt-3 rounded bg-[var(--color-primary)] px-4 py-2 text-white">
                    Schedule Daily
                </button>
                {message ? <p className="mt-2 text-sm">{message}</p> : null}
            </div>
        </section>
    );
}
