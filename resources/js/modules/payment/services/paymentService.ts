import axios, { AxiosError } from 'axios';
import type { PaymentEnvelope, PaymentIntent, PaymentTransaction } from '@modules/payment/types/payment';

const paymentHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<PaymentEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const paymentService = {
    async transactions(): Promise<PaymentTransaction[]> {
        try {
            const response = await paymentHttp.get<PaymentEnvelope<PaymentTransaction[]>>('/payment/transactions');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async createIntent(payload: { amount: number; currency: string; provider?: string }): Promise<PaymentIntent> {
        try {
            const response = await paymentHttp.post<PaymentEnvelope<PaymentIntent>>('/payment/intents', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
