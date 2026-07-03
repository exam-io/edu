import { useAcademicStore } from '@modules/academic/store/academicStore';

export function useAcademic() {
    const departments = useAcademicStore((state) => state.departments);
    const programs = useAcademicStore((state) => state.programs);
    const classes = useAcademicStore((state) => state.classes);
    const sections = useAcademicStore((state) => state.sections);
    const batches = useAcademicStore((state) => state.batches);
    const subjects = useAcademicStore((state) => state.subjects);
    const loading = useAcademicStore((state) => state.loading);
    const initialized = useAcademicStore((state) => state.initialized);
    const error = useAcademicStore((state) => state.error);
    const loadDepartments = useAcademicStore((state) => state.loadDepartments);
    const loadPrograms = useAcademicStore((state) => state.loadPrograms);
    const loadClasses = useAcademicStore((state) => state.loadClasses);
    const loadSections = useAcademicStore((state) => state.loadSections);
    const loadBatches = useAcademicStore((state) => state.loadBatches);
    const loadSubjects = useAcademicStore((state) => state.loadSubjects);
    const create = useAcademicStore((state) => state.create);
    const update = useAcademicStore((state) => state.update);
    const remove = useAcademicStore((state) => state.delete);
    const initialize = useAcademicStore((state) => state.initialize);

    return {
        departments,
        programs,
        classes,
        sections,
        batches,
        subjects,
        loading,
        initialized,
        error,
        loadDepartments,
        loadPrograms,
        loadClasses,
        loadSections,
        loadBatches,
        loadSubjects,
        create,
        update,
        delete: remove,
        initialize,
    };
}
