import { create } from 'zustand';
import { whiteLabelService } from '@modules/white-label/services/whiteLabelService';
import type { NavigationConfig, TenantDomain } from '@modules/white-label/types/whiteLabel';

interface WhiteLabelState {
    domains: TenantDomain[];
    navigation: NavigationConfig | null;
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    addDomain: (host: string) => Promise<void>;
}

export const useWhiteLabelStore = create<WhiteLabelState>((set, get) => ({
    domains: [],
    navigation: null,
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [domains, navigation] = await Promise.all([whiteLabelService.domains(), whiteLabelService.navigation()]);
            set({ domains, navigation });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load white-label data.' });
        } finally {
            set({ loading: false });
        }
    },

    addDomain: async (host) => {
        set({ loading: true, error: null });
        try {
            const domain = await whiteLabelService.createDomain({ host });
            set({ domains: [domain, ...get().domains] });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to add domain.' });
        } finally {
            set({ loading: false });
        }
    },
}));
