import { useEnrollmentStore } from '@modules/enrollments/store/enrollmentStore';

export function useEnrollments() {
    const enrollments = useEnrollmentStore((state) => state.enrollments);
    const assignments = useEnrollmentStore((state) => state.assignments);
    const loading = useEnrollmentStore((state) => state.loading);
    const initialized = useEnrollmentStore((state) => state.initialized);
    const error = useEnrollmentStore((state) => state.error);
    const loadEnrollments = useEnrollmentStore((state) => state.loadEnrollments);
    const loadAssignments = useEnrollmentStore((state) => state.loadAssignments);
    const createEnrollment = useEnrollmentStore((state) => state.createEnrollment);
    const updateEnrollment = useEnrollmentStore((state) => state.updateEnrollment);
    const deleteEnrollment = useEnrollmentStore((state) => state.deleteEnrollment);
    const createAssignment = useEnrollmentStore((state) => state.createAssignment);
    const updateAssignment = useEnrollmentStore((state) => state.updateAssignment);
    const deleteAssignment = useEnrollmentStore((state) => state.deleteAssignment);
    const initialize = useEnrollmentStore((state) => state.initialize);

    return {
        enrollments,
        assignments,
        loading,
        initialized,
        error,
        loadEnrollments,
        loadAssignments,
        createEnrollment,
        updateEnrollment,
        deleteEnrollment,
        createAssignment,
        updateAssignment,
        deleteAssignment,
        initialize,
    };
}
