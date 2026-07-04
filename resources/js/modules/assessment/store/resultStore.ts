import { create } from 'zustand';
import { assessmentService } from '@modules/assessment/services/assessmentService';
import type { AssessmentResult } from '@modules/assessment/types/assessment';

interface ResultState {
    result: AssessmentResult | null;
    ranking: number | null;
    analytics: { percentage: number; passed: boolean } | null;
    loading: boolean;
    error: string | null;
    fetchResult: (assessmentId: number) => Promise<void>;
}

export const useResultStore = create<ResultState>((set) => ({
    result: null,
    ranking: null,
    analytics: null,
    loading: false,
    error: null,

    fetchResult: async (assessmentId) => {
        set({ loading: true, error: null });
        try {
            const result = await assessmentService.getResult(assessmentId);
            set({
                result,
                ranking: result.rank ?? null,
                analytics: {
                    percentage: result.percentage,
                    passed: result.score >= result.passing_marks,
                },
                loading: false,
            });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to load result.' });
        }
    },
}));
