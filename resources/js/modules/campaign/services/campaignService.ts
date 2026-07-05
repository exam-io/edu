import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, Campaign } from '@modules/campaign/types/campaign';

const http = axios.create({ baseURL: '/api', withCredentials: true, headers: { Accept: 'application/json' } });

function errorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const campaignService = {
    async list(): Promise<Campaign[]> {
        try {
            const response = await http.get<ApiEnvelope<Campaign[]>>('/campaigns');
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },

    async launch(id: number): Promise<Campaign> {
        try {
            const response = await http.post<ApiEnvelope<Campaign>>(`/campaigns/${id}/launch`);
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },
};
