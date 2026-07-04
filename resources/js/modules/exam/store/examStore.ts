import { create } from 'zustand';
import { examService } from '@modules/exam/services/examService';
import type { ExamOverview } from '@modules/exam/types/exam';

interface ExamState {
    overview: ExamOverview | null;
    loading: boolean;
    error: string | null;
    fetchOverview: () => Promise<void>;
}

export const useExamStore = create<ExamState>((set) => ({
    overview: null,
    loading: false,
    error: null,
    fetchOverview: async () => {
        set({ loading: true, error: null });
        try {
            const overview = await examService.overview();
            set({ overview, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load exam overview.',
            });
        }
    },
}));
