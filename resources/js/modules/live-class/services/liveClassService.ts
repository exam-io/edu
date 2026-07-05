import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, LiveClassSession, PaginationMeta } from '@modules/live-class/types/liveClass';

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

export const liveClassService = {
    async list(params?: Record<string, unknown>): Promise<{ data: LiveClassSession[]; meta?: PaginationMeta }> {
        try {
            const response = await http.get<{ success: boolean; data: LiveClassSession[]; meta?: PaginationMeta }>('/live-classes', { params });
            return { data: response.data.data, meta: response.data.meta };
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async join(id: number): Promise<LiveClassSession> {
        try {
            const response = await http.post<ApiEnvelope<LiveClassSession>>(`/live-classes/${id}/join`);
            return response.data.data;
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
