import { create } from 'zustand';
import { questionBankService } from '@modules/question-bank/services/questionBankService';
import type { QuestionSet } from '@modules/question-bank/types/questionBank';

interface QuestionBankState {
    sets: QuestionSet[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
}

export const useQuestionBankStore = create<QuestionBankState>((set, get) => ({
    sets: [],
    loading: false,
    initialized: false,
    error: null,

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            const sets = await questionBankService.listSets();
            set({ sets, initialized: true, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load question bank.' });
        } finally {
            set({ loading: false });
        }
    },
}));
