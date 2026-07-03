import { StudentCrudManager } from '@modules/students/components/StudentCrudManager';
import { useStudents } from '@modules/students/hooks/useStudents';
import { useEffect } from 'react';

export function StudentsListPage() {
    const { students, loading, error, createStudent, updateStudent, deleteStudent, initialize } = useStudents();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <StudentCrudManager
            title="Students"
            description="Manage students and profile records."
            students={students}
            loading={loading}
            error={error}
            onCreate={async (payload) => {
                await createStudent(payload);
            }}
            onUpdate={async (id, payload) => {
                await updateStudent(id, payload);
            }}
            onDelete={async (id) => {
                await deleteStudent(id);
            }}
        />
    );
}
