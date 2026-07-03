import { PropsWithChildren, useEffect } from 'react';
import { useStudents } from '@modules/students/hooks/useStudents';

export function StudentProvider({ children }: PropsWithChildren) {
    const { initialized, initialize } = useStudents();

    useEffect(() => {
        if (!initialized) {
            void initialize();
        }
    }, [initialized, initialize]);

    return <>{children}</>;
}
