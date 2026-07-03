import { useEffect, type PropsWithChildren } from 'react';
import { useAcademicStore } from '@modules/academic/store/academicStore';

export function AcademicProvider({ children }: PropsWithChildren) {
    const initialize = useAcademicStore((state) => state.initialize);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return <>{children}</>;
}
