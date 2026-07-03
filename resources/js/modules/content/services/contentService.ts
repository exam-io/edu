import axios, { AxiosError } from 'axios';
import type { ContentEnvelope, ContentItem } from '@modules/content/types/content';

const contentHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ContentEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: ContentEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const contentService = {
    async listItems(): Promise<ContentItem[]> {
        try {
            const response = await contentHttp.get<ContentEnvelope<ContentItem[]>>('/content-items');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
