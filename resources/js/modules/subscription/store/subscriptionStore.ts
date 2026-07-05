import { create } from 'zustand';
import { subscriptionService } from '@modules/subscription/services/subscriptionService';
import type { SubscriptionPlan, TenantSubscription } from '@modules/subscription/types/subscription';

interface SubscriptionState {
    plans: SubscriptionPlan[];
    current: TenantSubscription | null;
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    renew: () => Promise<void>;
}

export const useSubscriptionStore = create<SubscriptionState>((set) => ({
    plans: [],
    current: null,
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [plans, current] = await Promise.all([subscriptionService.plans(), subscriptionService.current()]);
            set({ plans, current });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load subscription.' });
        } finally {
            set({ loading: false });
        }
    },

    renew: async () => {
        set({ loading: true, error: null });
        try {
            await subscriptionService.renew();
            const current = await subscriptionService.current();
            set({ current });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to request renewal.' });
        } finally {
            set({ loading: false });
        }
    },
}));
