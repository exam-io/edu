import { useEffect } from 'react';
import { useAssessmentStore } from '@modules/assessment/store/assessmentStore';

export function useAssessments() {
    const assessments = useAssessmentStore((state) => state.assessments);
    const loading = useAssessmentStore((state) => state.loading);
    const error = useAssessmentStore((state) => state.error);
    const fetchAssessments = useAssessmentStore((state) => state.fetchAssessments);

    useEffect(() => {
        void fetchAssessments();
    }, [fetchAssessments]);

    return { assessments, loading, error };
}
