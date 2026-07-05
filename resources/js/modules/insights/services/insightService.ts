import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, GeneratedInsight } from '@modules/insights/types/insight';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function getErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function unwrap<T>(envelope: ApiEnvelope<T>): T {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const insightService = {
    async listInsights(params?: Record<string, unknown>): Promise<GeneratedInsight[]> {
        try {
            const response = await http.get<ApiEnvelope<GeneratedInsight[]>>('/insights', { params });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async generate(): Promise<number> {
        try {
            const response = await http.post<ApiEnvelope<{ generated_count: number }>>('/insights/generate');
            return unwrap(response.data).generated_count;
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
