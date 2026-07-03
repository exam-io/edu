import { create } from 'zustand';
import { studentService } from '@modules/students/services/studentService';
import type { Student } from '@modules/students/types/student';

interface StudentState {
    students: Student[];
    selectedStudent: Student | null;
    loading: boolean;
    initialized: boolean;
    error: string | null;
    loadStudents: () => Promise<void>;
    selectStudent: (student: Student | null) => void;
    createStudent: (payload: Record<string, unknown>) => Promise<Student>;
    updateStudent: (id: number, payload: Record<string, unknown>) => Promise<Student>;
    deleteStudent: (id: number) => Promise<void>;
    initialize: () => Promise<void>;
}

export const useStudentStore = create<StudentState>((set, get) => ({
    students: [],
    selectedStudent: null,
    loading: false,
    initialized: false,
    error: null,

    loadStudents: async () => {
        try {
            const students = await studentService.list();
            set({ students, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load students.' });
        }
    },

    selectStudent: (student) => {
        set({ selectedStudent: student });
    },

    createStudent: async (payload) => {
        const created = await studentService.create(payload);
        set((state) => ({ students: [created, ...state.students], error: null }));
        return created;
    },

    updateStudent: async (id, payload) => {
        const updated = await studentService.update(id, payload);
        set((state) => ({
            students: state.students.map((item) => (item.id === id ? updated : item)),
            selectedStudent: state.selectedStudent?.id === id ? updated : state.selectedStudent,
            error: null,
        }));
        return updated;
    },

    deleteStudent: async (id) => {
        await studentService.delete(id);
        set((state) => ({
            students: state.students.filter((item) => item.id !== id),
            selectedStudent: state.selectedStudent?.id === id ? null : state.selectedStudent,
            error: null,
        }));
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await get().loadStudents();
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));
