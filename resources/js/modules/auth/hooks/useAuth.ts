import { useAuthStore } from '@modules/auth/store/authStore';

export function useAuth() {
    const user = useAuthStore((state) => state.user);
    const tenant = useAuthStore((state) => state.tenant);
    const roles = useAuthStore((state) => state.roles);
    const permissions = useAuthStore((state) => state.permissions);
    const settings = useAuthStore((state) => state.settings);
    const token = useAuthStore((state) => state.token);
    const isAuthenticated = useAuthStore((state) => state.isAuthenticated);
    const loading = useAuthStore((state) => state.loading);
    const login = useAuthStore((state) => state.login);
    const logout = useAuthStore((state) => state.logout);
    const fetchContext = useAuthStore((state) => state.fetchContext);
    const initialize = useAuthStore((state) => state.initialize);
    const setTheme = useAuthStore((state) => state.setTheme);
    const setLanguage = useAuthStore((state) => state.setLanguage);

    return {
        user,
        tenant,
        roles,
        permissions,
        settings,
        token,
        isAuthenticated,
        loading,
        login,
        logout,
        fetchContext,
        initialize,
        setTheme,
        setLanguage,
    };
}
