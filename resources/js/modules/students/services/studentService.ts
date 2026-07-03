import axios, { AxiosError } from 'axios';
import type { Student, StudentEnvelope } from '@modules/students/types/student';

const studentHttp = axios.create({
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

    const payload = error.response?.data as Partial<StudentEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: StudentEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const studentService = {
    async list(params?: Record<string, string | number | boolean | undefined>): Promise<Student[]> {
        try {
            const response = await studentHttp.get<StudentEnvelope<Student[]>>('/students', { params });
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async get(id: number): Promise<Student> {
        try {
            const response = await studentHttp.get<StudentEnvelope<Student>>(`/students/${id}`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async create(payload: Record<string, unknown>): Promise<Student> {
        try {
            const response = await studentHttp.post<StudentEnvelope<Student>>('/students', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update(id: number, payload: Record<string, unknown>): Promise<Student> {
        try {
            const response = await studentHttp.put<StudentEnvelope<Student>>(`/students/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async delete(id: number): Promise<void> {
        try {
            await studentHttp.delete(`/students/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
