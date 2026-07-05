import axios, { AxiosError } from 'axios';
import type { Announcement, ApiEnvelope, CommunicationHistoryItem } from '@modules/communication/types/communication';

const http = axios.create({ baseURL: '/api', withCredentials: true, headers: { Accept: 'application/json' } });

function errorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const communicationService = {
    async listAnnouncements(): Promise<Announcement[]> {
        try {
            const response = await http.get<ApiEnvelope<Announcement[]>>('/communications/announcements');
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },

    async listHistory(): Promise<CommunicationHistoryItem[]> {
        try {
            const response = await http.get<ApiEnvelope<CommunicationHistoryItem[]>>('/communications/history');
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },
};
