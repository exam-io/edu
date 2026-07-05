import { AxiosError } from 'axios';
import { http } from '@services/http';
import type { ApiEnvelope, SecurityPolicy, SystemHealthCheck } from '@modules/system/types/system';

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const systemService = {
    async policy(): Promise<SecurityPolicy> {
        try {
            const response = await http.get<ApiEnvelope<SecurityPolicy>>('/system/security-policy');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async updatePolicy(payload: Partial<SecurityPolicy>): Promise<SecurityPolicy> {
        try {
            const response = await http.put<ApiEnvelope<SecurityPolicy>>('/system/security-policy', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async healthChecks(refresh = false): Promise<SystemHealthCheck[]> {
        try {
            const response = await http.get<ApiEnvelope<SystemHealthCheck[]>>('/system/health-checks', {
                params: { refresh: refresh ? 1 : 0 },
            });
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
