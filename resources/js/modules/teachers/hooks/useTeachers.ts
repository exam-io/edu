import { useTeacherStore } from '@modules/teachers/store/teacherStore';

export function useTeachers() {
    const teachers = useTeacherStore((state) => state.teachers);
    const selectedTeacher = useTeacherStore((state) => state.selectedTeacher);
    const loading = useTeacherStore((state) => state.loading);
    const initialized = useTeacherStore((state) => state.initialized);
    const error = useTeacherStore((state) => state.error);
    const loadTeachers = useTeacherStore((state) => state.loadTeachers);
    const selectTeacher = useTeacherStore((state) => state.selectTeacher);
    const createTeacher = useTeacherStore((state) => state.createTeacher);
    const updateTeacher = useTeacherStore((state) => state.updateTeacher);
    const deleteTeacher = useTeacherStore((state) => state.deleteTeacher);
    const initialize = useTeacherStore((state) => state.initialize);

    return {
        teachers,
        selectedTeacher,
        loading,
        initialized,
        error,
        loadTeachers,
        selectTeacher,
        createTeacher,
        updateTeacher,
        deleteTeacher,
        initialize,
    };
}
