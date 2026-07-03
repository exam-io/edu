import axios, { AxiosError } from 'axios';
import type { MediaAsset, MediaEnvelope } from '@modules/media/types/media';

const mediaHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<MediaEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: MediaEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const mediaService = {
    async list(): Promise<MediaAsset[]> {
        try {
            const response = await mediaHttp.get<MediaEnvelope<MediaAsset[]>>('/media');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
