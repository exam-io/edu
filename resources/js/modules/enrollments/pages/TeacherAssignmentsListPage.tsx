import { TeacherAssignmentCrudManager } from '@modules/enrollments/components/TeacherAssignmentCrudManager';
import { useEnrollments } from '@modules/enrollments/hooks/useEnrollments';
import { useEffect } from 'react';

export function TeacherAssignmentsListPage() {
    const { assignments, loading, error, createAssignment, updateAssignment, deleteAssignment, initialize } = useEnrollments();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <TeacherAssignmentCrudManager
            title="Teacher Assignments"
            description="Manage teacher-class-subject assignments."
            assignments={assignments}
            loading={loading}
            error={error}
            onCreate={async (payload) => {
                await createAssignment(payload);
            }}
            onUpdate={async (id, payload) => {
                await updateAssignment(id, payload);
            }}
            onDelete={async (id) => {
                await deleteAssignment(id);
            }}
        />
    );
}
