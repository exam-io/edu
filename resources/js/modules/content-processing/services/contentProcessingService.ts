import axios, { AxiosError } from 'axios';
import type { ContentProcessingEnvelope, ContentSource } from '@modules/content-processing/types/contentProcessing';

const processingHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ContentProcessingEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: ContentProcessingEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const contentProcessingService = {
    async listSources(): Promise<ContentSource[]> {
        try {
            const response = await processingHttp.get<ContentProcessingEnvelope<ContentSource[]>>('/content-sources');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async createSource(payload: FormData | Record<string, unknown>): Promise<ContentSource> {
        try {
            const response = await processingHttp.post<ContentProcessingEnvelope<ContentSource>>('/content-sources', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async showSource(id: number): Promise<ContentSource> {
        try {
            const response = await processingHttp.get<ContentProcessingEnvelope<ContentSource>>(`/content-sources/${id}`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async retrySource(id: number): Promise<ContentSource> {
        try {
            const response = await processingHttp.post<ContentProcessingEnvelope<ContentSource>>(`/content-sources/${id}/retry`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async deleteSource(id: number): Promise<void> {
        try {
            await processingHttp.delete<ContentProcessingEnvelope<unknown>>(`/content-sources/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
