import { create } from 'zustand';
import { academicService } from '@modules/academic/services/academicService';
import type {
    AcademicClass,
    AcademicEntity,
    AcademicEntityKey,
    Batch,
    Department,
    Program,
    Section,
    Subject,
} from '@modules/academic/types/academic';

interface AcademicState {
    departments: Department[];
    programs: Program[];
    classes: AcademicClass[];
    sections: Section[];
    batches: Batch[];
    subjects: Subject[];
    loading: boolean;
    initialized: boolean;
    error: string | null;

    loadDepartments: () => Promise<void>;
    loadPrograms: () => Promise<void>;
    loadClasses: () => Promise<void>;
    loadSections: () => Promise<void>;
    loadBatches: () => Promise<void>;
    loadSubjects: () => Promise<void>;

    create: (resource: AcademicEntityKey, payload: Record<string, unknown>) => Promise<AcademicEntity>;
    update: (resource: AcademicEntityKey, id: number, payload: Record<string, unknown>) => Promise<AcademicEntity>;
    delete: (resource: AcademicEntityKey, id: number) => Promise<void>;

    initialize: () => Promise<void>;
}

function putResource<T extends AcademicEntity>(list: T[], next: T): T[] {
    return [next, ...list.filter((item) => item.id !== next.id)];
}

export const useAcademicStore = create<AcademicState>((set, get) => ({
    departments: [],
    programs: [],
    classes: [],
    sections: [],
    batches: [],
    subjects: [],
    loading: false,
    initialized: false,
    error: null,

    loadDepartments: async () => {
        try {
            const departments = await academicService.list<Department>('departments');
            set({ departments, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load departments.' });
        }
    },

    loadPrograms: async () => {
        try {
            const programs = await academicService.list<Program>('programs');
            set({ programs, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load programs.' });
        }
    },

    loadClasses: async () => {
        try {
            const classes = await academicService.list<AcademicClass>('classes');
            set({ classes, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load classes.' });
        }
    },

    loadSections: async () => {
        try {
            const sections = await academicService.list<Section>('sections');
            set({ sections, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load sections.' });
        }
    },

    loadBatches: async () => {
        try {
            const batches = await academicService.list<Batch>('batches');
            set({ batches, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load batches.' });
        }
    },

    loadSubjects: async () => {
        try {
            const subjects = await academicService.list<Subject>('subjects');
            set({ subjects, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load subjects.' });
        }
    },

    create: async (resource, payload) => {
        const created = await academicService.create(resource, payload);

        set((state) => {
            if (resource === 'departments') {
                return { departments: putResource(state.departments, created as Department) };
            }
            if (resource === 'programs') {
                return { programs: putResource(state.programs, created as Program) };
            }
            if (resource === 'classes') {
                return { classes: putResource(state.classes, created as AcademicClass) };
            }
            if (resource === 'sections') {
                return { sections: putResource(state.sections, created as Section) };
            }
            if (resource === 'batches') {
                return { batches: putResource(state.batches, created as Batch) };
            }

            return { subjects: putResource(state.subjects, created as Subject) };
        });

        set({ error: null });

        return created;
    },

    update: async (resource, id, payload) => {
        const updated = await academicService.update(resource, id, payload);

        set((state) => {
            if (resource === 'departments') {
                return {
                    departments: state.departments.map((item) => (item.id === id ? (updated as Department) : item)),
                };
            }
            if (resource === 'programs') {
                return {
                    programs: state.programs.map((item) => (item.id === id ? (updated as Program) : item)),
                };
            }
            if (resource === 'classes') {
                return {
                    classes: state.classes.map((item) => (item.id === id ? (updated as AcademicClass) : item)),
                };
            }
            if (resource === 'sections') {
                return {
                    sections: state.sections.map((item) => (item.id === id ? (updated as Section) : item)),
                };
            }
            if (resource === 'batches') {
                return {
                    batches: state.batches.map((item) => (item.id === id ? (updated as Batch) : item)),
                };
            }

            return {
                subjects: state.subjects.map((item) => (item.id === id ? (updated as Subject) : item)),
            };
        });

        set({ error: null });

        return updated;
    },

    delete: async (resource, id) => {
        await academicService.delete(resource, id);

        set((state) => {
            if (resource === 'departments') {
                return { departments: state.departments.filter((item) => item.id !== id) };
            }
            if (resource === 'programs') {
                return { programs: state.programs.filter((item) => item.id !== id) };
            }
            if (resource === 'classes') {
                return { classes: state.classes.filter((item) => item.id !== id) };
            }
            if (resource === 'sections') {
                return { sections: state.sections.filter((item) => item.id !== id) };
            }
            if (resource === 'batches') {
                return { batches: state.batches.filter((item) => item.id !== id) };
            }

            return { subjects: state.subjects.filter((item) => item.id !== id) };
        });
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });

        try {
            await Promise.all([
                get().loadDepartments(),
                get().loadPrograms(),
                get().loadClasses(),
                get().loadSections(),
                get().loadBatches(),
                get().loadSubjects(),
            ]);

            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));
