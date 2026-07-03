import { useMemo } from 'react';
import { useTeachers } from '@modules/teachers/hooks/useTeachers';

export function TeacherProfilePage() {
    const { selectedTeacher } = useTeachers();
    const fullName = useMemo(() => {
        if (!selectedTeacher) {
            return 'No teacher selected';
        }

        return `${selectedTeacher.first_name} ${selectedTeacher.last_name}`;
    }, [selectedTeacher]);

    return (
        <section>
            <h2>Teacher Profile</h2>
            <p>{fullName}</p>
        </section>
    );
}
