import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, ReportExecution, ReportSchedule, ReportTemplate } from '@modules/reporting/types/reporting';

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

export const reportingService = {
    async listTemplates(params?: Record<string, unknown>): Promise<ReportTemplate[]> {
        try {
            const response = await http.get<ApiEnvelope<ReportTemplate[]>>('/reports/templates', { params });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async createTemplate(payload: Record<string, unknown>): Promise<ReportTemplate> {
        try {
            const response = await http.post<ApiEnvelope<ReportTemplate>>('/reports/templates', payload);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async runTemplate(id: number, filters: Record<string, unknown> = {}): Promise<ReportExecution> {
        try {
            const response = await http.post<ApiEnvelope<ReportExecution>>(`/reports/templates/${id}/run`, { filters });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async schedule(payload: Record<string, unknown>): Promise<ReportSchedule> {
        try {
            const response = await http.post<ApiEnvelope<ReportSchedule>>('/reports/schedules', payload);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
