import { useEffect, type PropsWithChildren } from 'react';
import { useAuthStore } from '@modules/auth/store/authStore';

export function AuthProvider({ children }: PropsWithChildren) {
    const initialize = useAuthStore((state) => state.initialize);
    const bootstrapError = useAuthStore((state) => state.bootstrapError);
    const clearBootstrapError = useAuthStore((state) => state.clearBootstrapError);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <>
            {bootstrapError ? (
                <div className="fixed right-4 top-4 z-[1200] max-w-md rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900 shadow-lg">
                    <p className="font-medium">Session restored with limited context</p>
                    <p className="mt-1 text-xs text-amber-800">{bootstrapError}</p>
                    <button
                        type="button"
                        onClick={clearBootstrapError}
                        className="mt-2 rounded border border-amber-400 px-2 py-1 text-xs font-medium text-amber-900 hover:bg-amber-100"
                    >
                        Dismiss
                    </button>
                </div>
            ) : null}
            {children}
        </>
    );
}
