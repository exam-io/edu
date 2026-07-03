import { useMemo } from 'react';
import { useStudents } from '@modules/students/hooks/useStudents';

export function StudentProfilePage() {
    const { selectedStudent } = useStudents();
    const fullName = useMemo(() => {
        if (!selectedStudent) {
            return 'No student selected';
        }

        return `${selectedStudent.first_name} ${selectedStudent.last_name}`;
    }, [selectedStudent]);

    return (
        <section>
            <h2>Student Profile</h2>
            <p>{fullName}</p>
        </section>
    );
}
