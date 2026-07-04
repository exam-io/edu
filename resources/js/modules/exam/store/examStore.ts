import { create } from 'zustand';
import { examService } from '@modules/exam/services/examService';
import type { ExamOverview } from '@modules/exam/types/exam';

interface ExamState {
    overview: ExamOverview | null;
    loading: boolean;
    fetchOverview: () => Promise<void>;
}

export const useExamStore = create<ExamState>((set) => ({
    overview: null,
    loading: false,
    fetchOverview: async () => {
        set({ loading: true });
        try {
            const overview = await examService.overview();
            set({ overview, loading: false });
        } catch {
            set({ loading: false });
        }
    },
}));
