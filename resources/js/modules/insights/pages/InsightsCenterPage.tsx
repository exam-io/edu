import { useEffect } from 'react';
import { useInsightStore } from '@modules/insights/store/insightStore';

export function InsightsCenterPage() {
    const insights = useInsightStore((state) => state.insights);
    const loading = useInsightStore((state) => state.loading);
    const error = useInsightStore((state) => state.error);
    const fetchInsights = useInsightStore((state) => state.fetchInsights);
    const generateInsights = useInsightStore((state) => state.generateInsights);

    useEffect(() => {
        void fetchInsights();
    }, [fetchInsights]);

    return (
        <section className="space-y-4">
            <header className="flex items-center justify-between gap-3">
                <div>
                    <h2 className="text-xl font-semibold">Insights Center</h2>
                    <p className="text-sm text-[var(--color-muted)]">AI-free deterministic insights derived from analytics rules and thresholds.</p>
                </div>
                <button type="button" className="rounded bg-[var(--color-primary)] px-4 py-2 text-white" onClick={() => void generateInsights()}>
                    Generate Now
                </button>
            </header>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading insights...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <ul className="space-y-2 text-sm">
                {insights.map((insight) => (
                    <li key={insight.id} className="rounded-md border border-[var(--color-border)] px-3 py-2">
                        <p className="font-medium">{insight.title}</p>
                        <p className="text-[var(--color-muted)]">{insight.summary}</p>
                    </li>
                ))}
            </ul>
        </section>
    );
}
