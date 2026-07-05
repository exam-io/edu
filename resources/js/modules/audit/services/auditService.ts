import { AxiosError } from 'axios';
import { http } from '@services/http';
import type { ApiEnvelope, AuditLogEntry } from '@modules/audit/types/audit';

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const auditService = {
    async logs(): Promise<AuditLogEntry[]> {
        try {
            const response = await http.get<ApiEnvelope<AuditLogEntry[]>>('/audit/logs');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async store(payload: {
        action: string;
        resource_type: string;
        resource_id?: string;
        before_state?: Record<string, unknown>;
        after_state?: Record<string, unknown>;
        context?: Record<string, unknown>;
    }): Promise<void> {
        try {
            await http.post('/audit/logs', payload);
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
