import { create } from 'zustand';
import { lmsService } from '@modules/lms/services/lmsService';
import type { LmsEnrollment, LmsProgress } from '@modules/lms/types/lms';

interface LmsState {
    enrollments: LmsEnrollment[];
    progress: LmsProgress[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
}

export const useLmsStore = create<LmsState>((set, get) => ({
    enrollments: [],
    progress: [],
    loading: false,
    initialized: false,
    error: null,

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            const [enrollments, progress] = await Promise.all([
                lmsService.listEnrollments(),
                lmsService.listProgress(),
            ]);

            set({ enrollments, progress, initialized: true, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to initialize LMS store.' });
        } finally {
            set({ loading: false });
        }
    },
}));
