import { PropsWithChildren, useEffect } from 'react';
import { useParents } from '@modules/parents/hooks/useParents';

export function ParentProvider({ children }: PropsWithChildren) {
    const { initialized, initialize } = useParents();

    useEffect(() => {
        if (!initialized) {
            void initialize();
        }
    }, [initialized, initialize]);

    return <>{children}</>;
}
