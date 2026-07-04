import { create } from 'zustand';
import { assessmentService } from '@modules/assessment/services/assessmentService';
import type { Assessment } from '@modules/assessment/types/assessment';

interface AssessmentState {
    assessments: Assessment[];
    selectedAssessment: Assessment | null;
    questions: Assessment['questions'];
    loading: boolean;
    error: string | null;
    fetchAssessments: () => Promise<void>;
    selectAssessment: (assessment: Assessment | null) => void;
}

export const useAssessmentStore = create<AssessmentState>((set) => ({
    assessments: [],
    selectedAssessment: null,
    questions: [],
    loading: false,
    error: null,

    fetchAssessments: async () => {
        set({ loading: true, error: null });
        try {
            const assessments = await assessmentService.listAssessments();
            set({ assessments, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load assessments.',
            });
        }
    },

    selectAssessment: (assessment) => {
        set({ selectedAssessment: assessment, questions: assessment?.questions ?? [] });
    },
}));
