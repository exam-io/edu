import axios, { AxiosError } from 'axios';
import type { SubscriptionEnvelope, SubscriptionPlan, TenantSubscription } from '@modules/subscription/types/subscription';

const subscriptionHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<SubscriptionEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const subscriptionService = {
    async plans(): Promise<SubscriptionPlan[]> {
        try {
            const response = await subscriptionHttp.get<SubscriptionEnvelope<SubscriptionPlan[]>>('/subscription/plans');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async current(): Promise<TenantSubscription | null> {
        try {
            const response = await subscriptionHttp.get<SubscriptionEnvelope<TenantSubscription | null>>('/subscription/current');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async renew(): Promise<void> {
        try {
            await subscriptionHttp.post<SubscriptionEnvelope<unknown>>('/subscription/renew');
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
