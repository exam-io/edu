import { create } from 'zustand';
import { saasService } from '@modules/saas/services/saasService';
import type { SaaSDashboard, UsageCounter } from '@modules/saas/types/saas';

interface SaaSState {
    dashboard: SaaSDashboard | null;
    usage: UsageCounter[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    trackUsage: (metricKey: string, incrementBy: number) => Promise<void>;
}

export const useSaaSStore = create<SaaSState>((set) => ({
    dashboard: null,
    usage: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [dashboard, usage] = await Promise.all([saasService.dashboard(), saasService.usage()]);
            set({ dashboard, usage });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load SaaS analytics.' });
        } finally {
            set({ loading: false });
        }
    },

    trackUsage: async (metricKey, incrementBy) => {
        set({ loading: true, error: null });
        try {
            await saasService.track(metricKey, incrementBy);
            const usage = await saasService.usage();
            set({ usage });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to track usage.' });
        } finally {
            set({ loading: false });
        }
    },
}));
