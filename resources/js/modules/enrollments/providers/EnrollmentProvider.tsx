import { PropsWithChildren, useEffect } from 'react';
import { useEnrollments } from '@modules/enrollments/hooks/useEnrollments';

export function EnrollmentProvider({ children }: PropsWithChildren) {
    const { initialized, initialize } = useEnrollments();

    useEffect(() => {
        if (!initialized) {
            void initialize();
        }
    }, [initialized, initialize]);

    return <>{children}</>;
}
