import axios, { AxiosError } from 'axios';
import type { Teacher, TeacherEnvelope } from '@modules/teachers/types/teacher';

const teacherHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<TeacherEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: TeacherEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const teacherService = {
    async list(): Promise<Teacher[]> {
        try {
            const response = await teacherHttp.get<TeacherEnvelope<Teacher[]>>('/teachers');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async create(payload: Record<string, unknown>): Promise<Teacher> {
        try {
            const response = await teacherHttp.post<TeacherEnvelope<Teacher>>('/teachers', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update(id: number, payload: Record<string, unknown>): Promise<Teacher> {
        try {
            const response = await teacherHttp.put<TeacherEnvelope<Teacher>>(`/teachers/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async delete(id: number): Promise<void> {
        try {
            await teacherHttp.delete(`/teachers/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
