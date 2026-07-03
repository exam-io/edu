import { EnrollmentCrudManager } from '@modules/enrollments/components/EnrollmentCrudManager';
import { useEnrollments } from '@modules/enrollments/hooks/useEnrollments';
import { useEffect } from 'react';

export function EnrollmentsListPage() {
    const { enrollments, loading, error, createEnrollment, updateEnrollment, deleteEnrollment, initialize } = useEnrollments();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <EnrollmentCrudManager
            title="Enrollments"
            description="Manage student enrollments by class, section, and batch."
            enrollments={enrollments}
            loading={loading}
            error={error}
            onCreate={async (payload) => {
                await createEnrollment(payload);
            }}
            onUpdate={async (id, payload) => {
                await updateEnrollment(id, payload);
            }}
            onDelete={async (id) => {
                await deleteEnrollment(id);
            }}
        />
    );
}
