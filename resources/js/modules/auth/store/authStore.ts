import { create } from 'zustand';
import { authService } from '@modules/auth/services/authService';
import type { AuthSettings, AuthTenant, AuthUser, LoginPayload } from '@modules/auth/types/auth';
import { useLocaleStore } from '@stores/localeStore';
import { useThemeStore } from '@stores/themeStore';

interface AuthState {
    user: AuthUser | null;
    tenant: AuthTenant | null;
    roles: string[];
    permissions: string[];
    settings: AuthSettings | null;
    token: string | null;
    isAuthenticated: boolean;
    loading: boolean;
    bootstrapError: string | null;
    login: (payload: LoginPayload) => Promise<void>;
    logout: () => Promise<void>;
    fetchContext: () => Promise<void>;
    initialize: () => Promise<void>;
    setTheme: (theme: string) => void;
    setLanguage: (language: string) => void;
    clearBootstrapError: () => void;
}

const initialToken = authService.getStoredToken();

export const useAuthStore = create<AuthState>((set, get) => ({
    user: null,
    tenant: null,
    roles: [],
    permissions: [],
    settings: null,
    token: initialToken,
    isAuthenticated: Boolean(initialToken),
    loading: false,
    bootstrapError: null,

    login: async (payload) => {
        set({ loading: true });

        try {
            const result = await authService.login(payload);
            authService.setToken(result.token);

            set({
                token: result.token,
                isAuthenticated: true,
                bootstrapError: null,
            });

            await get().fetchContext();
        } finally {
            set({ loading: false });
        }
    },

    logout: async () => {
        set({ loading: true });

        try {
            await authService.logout();
        } finally {
            authService.setToken(null);
            set({
                user: null,
                tenant: null,
                roles: [],
                permissions: [],
                settings: null,
                token: null,
                isAuthenticated: false,
                bootstrapError: null,
                loading: false,
            });
        }
    },

    fetchContext: async () => {
        const context = await authService.fetchContext();

        set({
            user: context.user,
            tenant: context.tenant,
            roles: context.roles,
            permissions: context.permissions,
            settings: context.user.settings ?? null,
            isAuthenticated: true,
            bootstrapError: null,
        });

        if (context.user.settings?.theme) {
            useThemeStore.getState().setTheme(context.user.settings.theme as 'light' | 'dark');
        }

        if (context.user.settings?.language) {
            useLocaleStore.getState().setLanguage(context.user.settings.language as 'en' | 'hi');
        }
    },

    initialize: async () => {
        const token = get().token;

        if (!token) {
            set({ bootstrapError: null });
            return;
        }

        set({ loading: true, bootstrapError: null });
        authService.setToken(token);

        try {
            await get().fetchContext();
        } catch (error) {
            const message = error instanceof Error ? error.message.toLowerCase() : '';
            const isUnauthenticated = message.includes('unauthenticated');

            if (isUnauthenticated) {
                authService.setToken(null);
                set({
                    user: null,
                    tenant: null,
                    roles: [],
                    permissions: [],
                    settings: null,
                    token: null,
                    isAuthenticated: false,
                    bootstrapError: null,
                });
                return;
            }

            // Keep current token/session state on non-auth failures (tenant/network/transient errors).
            const fallbackMessage = 'Unable to restore full session context. Check API/server tenant configuration and try refreshing again.';
            set({
                isAuthenticated: true,
                bootstrapError:
                    error instanceof Error && error.message.trim() !== '' && error.message !== 'Request failed.'
                        ? error.message
                        : fallbackMessage,
            });
        } finally {
            set({ loading: false });
        }
    },

    setTheme: (theme) => {
        const nextTheme = theme === 'dark' ? 'dark' : 'light';
        useThemeStore.getState().setTheme(nextTheme);
        set((state) => ({
            settings: state.settings ? { ...state.settings, theme: nextTheme } : state.settings,
        }));
    },

    setLanguage: (language) => {
        const nextLanguage = language === 'hi' ? 'hi' : 'en';
        useLocaleStore.getState().setLanguage(nextLanguage);
        set((state) => ({
            settings: state.settings ? { ...state.settings, language: nextLanguage } : state.settings,
        }));
    },

    clearBootstrapError: () => {
        set({ bootstrapError: null });
    },
}));
