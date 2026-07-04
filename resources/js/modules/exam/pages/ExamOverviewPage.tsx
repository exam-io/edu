import { useEffect, useState } from 'react';
import { examService } from '@modules/exam/services/examService';
import type { ExamOverview } from '@modules/exam/types/exam';

export function ExamOverviewPage() {
    const [overview, setOverview] = useState<ExamOverview | null>(null);

    useEffect(() => {
        examService.overview().then(setOverview).catch(() => undefined);
    }, []);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Exam Overview</h2>
            <pre className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4 text-xs">
                {JSON.stringify(overview, null, 2)}
            </pre>
        </section>
    );
}
