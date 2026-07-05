import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, CalendarEvent } from '@modules/calendar/types/calendar';

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

export const calendarService = {
    async listEvents(params?: Record<string, unknown>): Promise<CalendarEvent[]> {
        try {
            const response = await http.get<{ success: boolean; data: CalendarEvent[] }>('/calendar/events', { params });
            return response.data.data;
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};
