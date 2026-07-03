import { useEffect, type PropsWithChildren } from 'react';
import { useAuthStore } from '@modules/auth/store/authStore';

export function AuthProvider({ children }: PropsWithChildren) {
    const initialize = useAuthStore((state) => state.initialize);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return <>{children}</>;
}
