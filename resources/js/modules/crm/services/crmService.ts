import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, Lead } from '@modules/crm/types/crm';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function errorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const crmService = {
    async list(params?: Record<string, unknown>): Promise<Lead[]> {
        try {
            const response = await http.get<ApiEnvelope<Lead[]>>('/crm/leads', { params });
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },

    async create(payload: Record<string, unknown>): Promise<Lead> {
        try {
            const response = await http.post<ApiEnvelope<Lead>>('/crm/leads', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },
};
