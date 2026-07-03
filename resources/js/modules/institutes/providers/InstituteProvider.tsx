import { useEffect, type PropsWithChildren } from 'react';
import { useInstituteStore } from '@modules/institutes/store/instituteStore';

export function InstituteProvider({ children }: PropsWithChildren) {
    const initialize = useInstituteStore((state) => state.initialize);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return <>{children}</>;
}
