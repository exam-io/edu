import { create } from 'zustand';
import { roleDashboardService, type DashboardPayload } from '@modules/dashboard/services/roleDashboardService';

interface RoleDashboardState {
    dashboard: DashboardPayload | null;
    loading: boolean;
    error: string | null;
    loadDashboard: () => Promise<void>;
}

export const useRoleDashboardStore = create<RoleDashboardState>((set) => ({
    dashboard: null,
    loading: false,
    error: null,

    loadDashboard: async () => {
        set({ loading: true, error: null });
        try {
            const dashboard = await roleDashboardService.loadMyDashboard();
            set({ dashboard, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load dashboard.',
            });
        }
    },
}));
