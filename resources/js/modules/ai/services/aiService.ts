import axios, { AxiosError } from 'axios';
import type { AIGenerationRequest, AIEnvelope } from '@modules/ai/types/ai';

const aiHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<AIEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: AIEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const aiService = {
    async list(): Promise<AIGenerationRequest[]> {
        try {
            const response = await aiHttp.get<AIEnvelope<AIGenerationRequest[]>>('/ai-generation-requests');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
