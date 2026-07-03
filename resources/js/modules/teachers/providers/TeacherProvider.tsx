import { PropsWithChildren, useEffect } from 'react';
import { useTeachers } from '@modules/teachers/hooks/useTeachers';

export function TeacherProvider({ children }: PropsWithChildren) {
    const { initialized, initialize } = useTeachers();

    useEffect(() => {
        if (!initialized) {
            void initialize();
        }
    }, [initialized, initialize]);

    return <>{children}</>;
}
