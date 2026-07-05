import { useEffect } from 'react';
import { useReportingStore } from '@modules/reporting/store/reportingStore';

export function ReportLibraryPage() {
    const templates = useReportingStore((state) => state.templates);
    const loading = useReportingStore((state) => state.loading);
    const error = useReportingStore((state) => state.error);
    const fetchTemplates = useReportingStore((state) => state.fetchTemplates);

    useEffect(() => {
        void fetchTemplates();
    }, [fetchTemplates]);

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Report Library</h2>
                <p className="text-sm text-[var(--color-muted)]">Reusable templates for custom and scheduled reports.</p>
            </header>
            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading templates...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <ul className="space-y-2">
                {templates.map((template) => (
                    <li key={template.id} className="rounded-md border border-[var(--color-border)] px-3 py-2 text-sm">
                        <span className="font-medium">{template.name}</span>
                        <span className="ml-2 text-[var(--color-muted)]">{template.source}</span>
                    </li>
                ))}
            </ul>
        </section>
    );
}
