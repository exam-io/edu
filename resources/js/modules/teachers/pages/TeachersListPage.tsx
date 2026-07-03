import { TeacherCrudManager } from '@modules/teachers/components/TeacherCrudManager';
import { useTeachers } from '@modules/teachers/hooks/useTeachers';
import { useEffect } from 'react';

export function TeachersListPage() {
    const { teachers, loading, error, createTeacher, updateTeacher, deleteTeacher, initialize } = useTeachers();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <TeacherCrudManager
            title="Teachers"
            description="Manage teacher profiles and status."
            teachers={teachers}
            loading={loading}
            error={error}
            onCreate={async (payload) => {
                await createTeacher(payload);
            }}
            onUpdate={async (id, payload) => {
                await updateTeacher(id, payload);
            }}
            onDelete={async (id) => {
                await deleteTeacher(id);
            }}
        />
    );
}
