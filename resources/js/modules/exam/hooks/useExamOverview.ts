import { useEffect, useState } from 'react';
import { examService } from '@modules/exam/services/examService';
import type { ExamOverview } from '@modules/exam/types/exam';

export function useExamOverview() {
    const [overview, setOverview] = useState<ExamOverview | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        examService.overview().then(setOverview).finally(() => setLoading(false));
    }, []);

    return { overview, loading };
}
