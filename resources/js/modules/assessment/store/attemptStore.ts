import { create } from 'zustand';
import { assessmentService } from '@modules/assessment/services/assessmentService';
import type { AssessmentAttempt } from '@modules/assessment/types/assessment';

interface AttemptState {
    currentAttempt: AssessmentAttempt | null;
    answers: Record<number, unknown[]>;
    timer: number;
    loading: boolean;
    error: string | null;
    startAttempt: (assessmentId: number) => Promise<void>;
    saveAnswer: (assessmentId: number, questionId: number, selectedAnswer: unknown[], markForReview?: boolean) => Promise<void>;
    tick: () => void;
    setTimer: (seconds: number) => void;
}

export const useAttemptStore = create<AttemptState>((set, get) => ({
    currentAttempt: null,
    answers: {},
    timer: 0,
    loading: false,
    error: null,

    startAttempt: async (assessmentId) => {
        set({ loading: true, error: null });
        try {
            const currentAttempt = await assessmentService.startAttempt(assessmentId);
            set({ currentAttempt, loading: false });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to start attempt.' });
        }
    },

    saveAnswer: async (assessmentId, questionId, selectedAnswer, markForReview = false) => {
        set({ loading: true, error: null });
        try {
            const currentAttempt = await assessmentService.saveAnswer(assessmentId, questionId, selectedAnswer, markForReview);
            set({
                currentAttempt,
                loading: false,
                answers: { ...get().answers, [questionId]: selectedAnswer },
            });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to save answer.' });
        }
    },

    tick: () => set((state) => ({ timer: state.timer + 1 })),
    setTimer: (seconds) => set({ timer: seconds }),
}));
