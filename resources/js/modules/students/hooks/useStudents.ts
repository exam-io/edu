import { useStudentStore } from '@modules/students/store/studentStore';

export function useStudents() {
    const students = useStudentStore((state) => state.students);
    const selectedStudent = useStudentStore((state) => state.selectedStudent);
    const loading = useStudentStore((state) => state.loading);
    const initialized = useStudentStore((state) => state.initialized);
    const error = useStudentStore((state) => state.error);
    const loadStudents = useStudentStore((state) => state.loadStudents);
    const selectStudent = useStudentStore((state) => state.selectStudent);
    const createStudent = useStudentStore((state) => state.createStudent);
    const updateStudent = useStudentStore((state) => state.updateStudent);
    const deleteStudent = useStudentStore((state) => state.deleteStudent);
    const initialize = useStudentStore((state) => state.initialize);

    return {
        students,
        selectedStudent,
        loading,
        initialized,
        error,
        loadStudents,
        selectStudent,
        createStudent,
        updateStudent,
        deleteStudent,
        initialize,
    };
}
