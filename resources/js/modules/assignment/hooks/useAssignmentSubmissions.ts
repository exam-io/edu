import { useEffect } from 'react';
import { useAssignmentStore } from '@modules/assignment/store/assignmentStore';

export function useAssignmentSubmissions() {
    const submissions = useAssignmentStore((state) => state.submissions);
    const loading = useAssignmentStore((state) => state.loading);
    const error = useAssignmentStore((state) => state.error);
    const fetchSubmissions = useAssignmentStore((state) => state.fetchSubmissions);

    useEffect(() => {
        void fetchSubmissions();
    }, [fetchSubmissions]);

    return { submissions, loading, error };
}
