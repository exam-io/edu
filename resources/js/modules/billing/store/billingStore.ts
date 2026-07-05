import { create } from 'zustand';
import { billingService } from '@modules/billing/services/billingService';
import type { BillingCenter, BillingProfile, Invoice } from '@modules/billing/types/billing';

interface BillingState {
    center: BillingCenter | null;
    invoices: Invoice[];
    profile: BillingProfile | null;
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    saveProfile: (payload: Partial<BillingProfile>) => Promise<void>;
}

export const useBillingStore = create<BillingState>((set) => ({
    center: null,
    invoices: [],
    profile: null,
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [center, invoices] = await Promise.all([billingService.center(), billingService.invoices()]);
            set({ center, invoices });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load billing center.' });
        } finally {
            set({ loading: false });
        }
    },

    saveProfile: async (payload) => {
        set({ loading: true, error: null });
        try {
            const profile = await billingService.upsertProfile(payload);
            set({ profile });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to save billing profile.' });
        } finally {
            set({ loading: false });
        }
    },
}));
