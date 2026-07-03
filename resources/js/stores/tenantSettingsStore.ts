import { create } from 'zustand';

interface TenantSettingsState {
    tenantDefaultLanguage: 'en' | 'hi';
    tenantPrimaryColor: string;
    tenantSecondaryColor: string;
    setTenantDefaults: (input: {
        tenantDefaultLanguage?: 'en' | 'hi';
        tenantPrimaryColor?: string;
        tenantSecondaryColor?: string;
    }) => void;
}

export const useTenantSettingsStore = create<TenantSettingsState>((set) => ({
    tenantDefaultLanguage: 'en',
    tenantPrimaryColor: '#0b6eff',
    tenantSecondaryColor: '#00a889',
    setTenantDefaults: (input) => {
        set((state) => ({
            tenantDefaultLanguage: input.tenantDefaultLanguage ?? state.tenantDefaultLanguage,
            tenantPrimaryColor: input.tenantPrimaryColor ?? state.tenantPrimaryColor,
            tenantSecondaryColor: input.tenantSecondaryColor ?? state.tenantSecondaryColor,
        }));
    },
}));
