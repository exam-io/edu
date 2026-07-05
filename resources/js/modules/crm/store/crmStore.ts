import { create } from 'zustand';
import { crmService } from '@modules/crm/services/crmService';
import type { Lead } from '@modules/crm/types/crm';

interface CRMState {
    leads: Lead[];
    loading: boolean;
    error: string | null;
    fetchLeads: () => Promise<void>;
    createLead: (payload: Record<string, unknown>) => Promise<void>;
}

export const useCRMStore = create<CRMState>((set) => ({
    leads: [],
    loading: false,
    error: null,

    fetchLeads: async () => {
        set({ loading: true, error: null });
        try {
            const leads = await crmService.list();
            set({ leads, loading: false });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to load leads.' });
        }
    },

    createLead: async (payload) => {
        set({ loading: true, error: null });
        try {
            const created = await crmService.create(payload);
            set((state) => ({ leads: [created, ...state.leads], loading: false }));
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to create lead.' });
        }
    },
}));
