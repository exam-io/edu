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
};
