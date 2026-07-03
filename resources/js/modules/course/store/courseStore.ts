import { create } from 'zustand';
import { courseService } from '@modules/course/services/courseService';
import type { Course } from '@modules/course/types/course';

interface CourseState {
    items: Course[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    load: () => Promise<void>;
    initialize: () => Promise<void>;
}

export const useCourseStore = create<CourseState>((set, get) => ({
    items: [],
    loading: false,
    initialized: false,
    error: null,

    load: async () => {
        try {
            const items = await courseService.list();
            set({ items, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load courses.' });
        }
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await get().load();
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));
