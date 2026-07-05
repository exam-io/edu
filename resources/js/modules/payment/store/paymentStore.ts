import { create } from 'zustand';
import { paymentService } from '@modules/payment/services/paymentService';
import type { PaymentIntent, PaymentTransaction } from '@modules/payment/types/payment';

interface PaymentState {
    transactions: PaymentTransaction[];
    latestIntent: PaymentIntent | null;
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    createIntent: (amount: number) => Promise<void>;
}

export const usePaymentStore = create<PaymentState>((set) => ({
    transactions: [],
    latestIntent: null,
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const transactions = await paymentService.transactions();
            set({ transactions });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load payments.' });
        } finally {
            set({ loading: false });
        }
    },

    createIntent: async (amount) => {
        set({ loading: true, error: null });
        try {
            const latestIntent = await paymentService.createIntent({ amount, currency: 'USD', provider: 'null' });
            const transactions = await paymentService.transactions();
            set({ latestIntent, transactions });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to create payment intent.' });
        } finally {
            set({ loading: false });
        }
    },
}));
