import axios, { AxiosError } from 'axios';
import type { ParentEnvelope, ParentProfile } from '@modules/parents/types/parent';

const parentHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ParentEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: ParentEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const parentService = {
    async list(): Promise<ParentProfile[]> {
        try {
            const response = await parentHttp.get<ParentEnvelope<ParentProfile[]>>('/parents');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async create(payload: Record<string, unknown>): Promise<ParentProfile> {
        try {
            const response = await parentHttp.post<ParentEnvelope<ParentProfile>>('/parents', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update(id: number, payload: Record<string, unknown>): Promise<ParentProfile> {
        try {
            const response = await parentHttp.put<ParentEnvelope<ParentProfile>>(`/parents/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async delete(id: number): Promise<void> {
        try {
            await parentHttp.delete(`/parents/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
