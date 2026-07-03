import { create } from 'zustand';
import type {
    Tenant,
    TenantBranding,
    TenantConfiguration,
} from '@modules/tenant/types/tenant';

interface TenantState {
    tenant: Tenant | null;
    branding: TenantBranding | null;
    configuration: TenantConfiguration | null;
    isLoading: boolean;
    error: string | null;

    setTenant: (tenant: Tenant | null) => void;
    setBranding: (branding: TenantBranding | null) => void;
    setConfiguration: (configuration: TenantConfiguration | null) => void;
    setLoading: (loading: boolean) => void;
    setError: (error: string | null) => void;

    bootstrap: (tenant: Tenant, branding: TenantBranding, configuration: TenantConfiguration) => void;
    clear: () => void;
}

export const useTenantStore = create<TenantState>((set) => ({
    tenant: null,
    branding: null,
    configuration: null,
    isLoading: false,
    error: null,

    setTenant: (tenant) => set({ tenant }),
    setBranding: (branding) => set({ branding }),
    setConfiguration: (configuration) => set({ configuration }),
    setLoading: (isLoading) => set({ isLoading }),
    setError: (error) => set({ error }),

    bootstrap: (tenant, branding, configuration) => {
        set({ tenant, branding, configuration });
    },

    clear: () => {
        set({
            tenant: null,
            branding: null,
            configuration: null,
            error: null,
        });
    },
}));
