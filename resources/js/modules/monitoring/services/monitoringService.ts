import { AxiosError } from 'axios';
import { http } from '@services/http';
import type { AlertIncident, ApiEnvelope, MetricSnapshot } from '@modules/monitoring/types/monitoring';

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const monitoringService = {
    async metrics(): Promise<MetricSnapshot[]> {
        try {
            const response = await http.get<ApiEnvelope<MetricSnapshot[]>>('/monitoring/metrics');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async aggregate(payload: { metric_key: string; period_key: string; value: number; meta?: Record<string, unknown> }): Promise<AlertIncident[]> {
        try {
            const response = await http.post<ApiEnvelope<AlertIncident[]>>('/monitoring/metrics/aggregate', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
