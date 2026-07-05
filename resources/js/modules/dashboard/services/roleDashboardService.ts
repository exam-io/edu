import axios, { AxiosError } from 'axios';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface DashboardPayload {
    id: number;
    name: string;
    role_key: string;
    widgets?: Array<{
        id: number;
        widget_key: string;
        title: string;
        sort_order: number;
        config?: Record<string, unknown>;
    }>;
}

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

export const roleDashboardService = {
    async loadMyDashboard(): Promise<DashboardPayload> {
        try {
            const response = await http.get<ApiEnvelope<DashboardPayload>>('/dashboards/me');
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async savePreferences(preferences: Record<string, unknown>, dashboardDefinitionId?: number): Promise<void> {
        try {
            await http.put('/dashboards/preferences', {
                dashboard_definition_id: dashboardDefinitionId,
                preferences,
            });
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
