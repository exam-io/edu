import { create } from 'zustand';
import { enrollmentService } from '@modules/enrollments/services/enrollmentService';
import type { Enrollment, TeacherAssignment } from '@modules/enrollments/types/enrollment';

interface EnrollmentState {
    enrollments: Enrollment[];
    assignments: TeacherAssignment[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    loadEnrollments: () => Promise<void>;
    loadAssignments: () => Promise<void>;
    createEnrollment: (payload: Record<string, unknown>) => Promise<Enrollment>;
    updateEnrollment: (id: number, payload: Record<string, unknown>) => Promise<Enrollment>;
    deleteEnrollment: (id: number) => Promise<void>;
    createAssignment: (payload: Record<string, unknown>) => Promise<TeacherAssignment>;
    updateAssignment: (id: number, payload: Record<string, unknown>) => Promise<TeacherAssignment>;
    deleteAssignment: (id: number) => Promise<void>;
    initialize: () => Promise<void>;
}

export const useEnrollmentStore = create<EnrollmentState>((set, get) => ({
    enrollments: [],
    assignments: [],
    loading: false,
    initialized: false,
    error: null,

    loadEnrollments: async () => {
        try {
            const enrollments = await enrollmentService.listEnrollments();
            set({ enrollments, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load enrollments.' });
        }
    },

    loadAssignments: async () => {
        try {
            const assignments = await enrollmentService.listTeacherAssignments();
            set({ assignments, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load teacher assignments.' });
        }
    },

    createEnrollment: async (payload) => {
        const created = await enrollmentService.createEnrollment(payload);
        set((state) => ({ enrollments: [created, ...state.enrollments], error: null }));
        return created;
    },

    updateEnrollment: async (id, payload) => {
        const updated = await enrollmentService.updateEnrollment(id, payload);
        set((state) => ({
            enrollments: state.enrollments.map((item) => (item.id === id ? updated : item)),
            error: null,
        }));
        return updated;
    },

    deleteEnrollment: async (id) => {
        await enrollmentService.deleteEnrollment(id);
        set((state) => ({
            enrollments: state.enrollments.filter((item) => item.id !== id),
            error: null,
        }));
    },

    createAssignment: async (payload) => {
        const created = await enrollmentService.createTeacherAssignment(payload);
        set((state) => ({ assignments: [created, ...state.assignments], error: null }));
        return created;
    },

    updateAssignment: async (id, payload) => {
        const updated = await enrollmentService.updateTeacherAssignment(id, payload);
        set((state) => ({
            assignments: state.assignments.map((item) => (item.id === id ? updated : item)),
            error: null,
        }));
        return updated;
    },

    deleteAssignment: async (id) => {
        await enrollmentService.deleteTeacherAssignment(id);
        set((state) => ({
            assignments: state.assignments.filter((item) => item.id !== id),
            error: null,
        }));
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await Promise.all([get().loadEnrollments(), get().loadAssignments()]);
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));
