import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, TenantNotification } from '@modules/notification/types/notification';

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

export const notificationService = {
    async list(): Promise<TenantNotification[]> {
        try {
            const response = await http.get<{ success: boolean; data: TenantNotification[] }>('/notifications');
            return response.data.data;
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
