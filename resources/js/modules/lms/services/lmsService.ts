import axios, { AxiosError } from 'axios';
import type { LmsEnrollment, LmsEnvelope, LmsProgress } from '@modules/lms/types/lms';

const lmsHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<LmsEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: LmsEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const lmsService = {
    async listEnrollments(): Promise<LmsEnrollment[]> {
        try {
            const response = await lmsHttp.get<LmsEnvelope<LmsEnrollment[]>>('/lms/enrollments');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async listProgress(): Promise<LmsProgress[]> {
        try {
            const response = await lmsHttp.get<LmsEnvelope<LmsProgress[]>>('/lms/progress');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
