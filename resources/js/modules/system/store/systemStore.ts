import { create } from 'zustand';
import { systemService } from '@modules/system/services/systemService';
import type { SecurityPolicy, SystemHealthCheck } from '@modules/system/types/system';

interface SystemState {
    policy: SecurityPolicy | null;
    checks: SystemHealthCheck[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    refreshChecks: () => Promise<void>;
}

export const useSystemStore = create<SystemState>((set) => ({
    policy: null,
    checks: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [policy, checks] = await Promise.all([systemService.policy(), systemService.healthChecks(false)]);
            set({ policy, checks });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load system data.' });
        } finally {
            set({ loading: false });
        }
    },

    refreshChecks: async () => {
        set({ loading: true, error: null });
        try {
            const checks = await systemService.healthChecks(true);
            set({ checks });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to refresh health checks.' });
        } finally {
            set({ loading: false });
        }
    },
}));
