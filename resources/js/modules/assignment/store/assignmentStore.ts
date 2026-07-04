import { create } from 'zustand';
import { assignmentService } from '@modules/assignment/services/assignmentService';
import type { AssignmentSubmission } from '@modules/assignment/types/assignment';

interface AssignmentState {
    submissions: AssignmentSubmission[];
    loading: boolean;
    error: string | null;
    fetchSubmissions: () => Promise<void>;
}

export const useAssignmentStore = create<AssignmentState>((set) => ({
    submissions: [],
    loading: false,
    error: null,

    fetchSubmissions: async () => {
        set({ loading: true, error: null });
        try {
            const submissions = await assignmentService.listSubmissions();
            set({ submissions, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load submissions.',
            });
        }
    },
}));
