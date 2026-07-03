import axios, { AxiosError } from 'axios';
import type { AcademicEnvelope, AcademicEntity, AcademicEntityKey } from '@modules/academic/types/academic';

const academicHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<AcademicEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: AcademicEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const academicService = {
    async list<TData extends AcademicEntity>(
        resource: AcademicEntityKey,
        params?: Record<string, string | number | boolean | undefined>
    ): Promise<TData[]> {
        try {
            const response = await academicHttp.get<AcademicEnvelope<TData[]>>(`/${resource}`, { params });
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async create<TData extends AcademicEntity>(resource: AcademicEntityKey, payload: Record<string, unknown>): Promise<TData> {
        try {
            const response = await academicHttp.post<AcademicEnvelope<TData>>(`/${resource}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update<TData extends AcademicEntity>(resource: AcademicEntityKey, id: number, payload: Record<string, unknown>): Promise<TData> {
        try {
            const response = await academicHttp.put<AcademicEnvelope<TData>>(`/${resource}/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async delete(resource: AcademicEntityKey, id: number): Promise<void> {
        try {
            await academicHttp.delete(`/${resource}/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
