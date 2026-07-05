import { AxiosError } from 'axios';
import { http } from '@services/http';
import type { ApiEnvelope, BackupExecution, QueueOperationLog } from '@modules/operations/types/operations';

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const operationsService = {
    async latestBackup(): Promise<BackupExecution | null> {
        try {
            const response = await http.get<ApiEnvelope<BackupExecution | null>>('/operations/backups/latest');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async triggerBackup(): Promise<void> {
        try {
            await http.post('/operations/backups/trigger');
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async queueLogs(): Promise<QueueOperationLog[]> {
        try {
            const response = await http.get<ApiEnvelope<QueueOperationLog[]>>('/operations/queue/logs');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async recordQueueOperation(operation: string, meta: Record<string, unknown> = {}): Promise<void> {
        try {
            await http.post('/operations/queue/logs', { operation, meta });
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
