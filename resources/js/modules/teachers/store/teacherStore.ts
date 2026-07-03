import { create } from 'zustand';
import { teacherService } from '@modules/teachers/services/teacherService';
import type { Teacher } from '@modules/teachers/types/teacher';

interface TeacherState {
    teachers: Teacher[];
    selectedTeacher: Teacher | null;
    loading: boolean;
    initialized: boolean;
    error: string | null;
    loadTeachers: () => Promise<void>;
    selectTeacher: (teacher: Teacher | null) => void;
    createTeacher: (payload: Record<string, unknown>) => Promise<Teacher>;
    updateTeacher: (id: number, payload: Record<string, unknown>) => Promise<Teacher>;
    deleteTeacher: (id: number) => Promise<void>;
    initialize: () => Promise<void>;
}

export const useTeacherStore = create<TeacherState>((set, get) => ({
    teachers: [],
    selectedTeacher: null,
    loading: false,
    initialized: false,
    error: null,

    loadTeachers: async () => {
        try {
            const teachers = await teacherService.list();
            set({ teachers, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load teachers.' });
        }
    },

    selectTeacher: (teacher) => set({ selectedTeacher: teacher }),

    createTeacher: async (payload) => {
        const created = await teacherService.create(payload);
        set((state) => ({ teachers: [created, ...state.teachers], error: null }));
        return created;
    },

    updateTeacher: async (id, payload) => {
        const updated = await teacherService.update(id, payload);
        set((state) => ({
            teachers: state.teachers.map((item) => (item.id === id ? updated : item)),
            selectedTeacher: state.selectedTeacher?.id === id ? updated : state.selectedTeacher,
            error: null,
        }));
        return updated;
    },

    deleteTeacher: async (id) => {
        await teacherService.delete(id);
        set((state) => ({
            teachers: state.teachers.filter((item) => item.id !== id),
            selectedTeacher: state.selectedTeacher?.id === id ? null : state.selectedTeacher,
            error: null,
        }));
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await get().loadTeachers();
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));
