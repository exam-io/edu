import axios, { AxiosError } from 'axios';
import type { AnalyticsEvent, ApiEnvelope, MetricSnapshot } from '@modules/analytics/types/analytics';

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

export const analyticsService = {
    async listEvents(params?: Record<string, unknown>): Promise<AnalyticsEvent[]> {
        try {
            const response = await http.get<ApiEnvelope<AnalyticsEvent[]>>('/analytics/events', { params });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async metricSeries(metricKey = 'events.count'): Promise<MetricSnapshot[]> {
        try {
            const response = await http.get<ApiEnvelope<MetricSnapshot[]>>('/analytics/metrics', {
                params: { metric_key: metricKey },
            });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
