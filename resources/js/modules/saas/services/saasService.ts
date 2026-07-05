import axios, { AxiosError } from 'axios';
import type { SaaSDashboard, SaaSEnvelope, UsageCounter } from '@modules/saas/types/saas';

const saasHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<SaaSEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const saasService = {
    async dashboard(): Promise<SaaSDashboard> {
        try {
            const response = await saasHttp.get<SaaSEnvelope<SaaSDashboard>>('/saas/dashboard');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async usage(): Promise<UsageCounter[]> {
        try {
            const response = await saasHttp.get<SaaSEnvelope<UsageCounter[]>>('/saas/usage');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async track(metricKey: string, incrementBy: number): Promise<void> {
        try {
            await saasHttp.post('/saas/usage/track', { metric_key: metricKey, increment_by: incrementBy });
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
