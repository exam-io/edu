import axios, { AxiosError } from 'axios';
import type { Enrollment, EnrollmentEnvelope, TeacherAssignment } from '@modules/enrollments/types/enrollment';

const enrollmentHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<EnrollmentEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: EnrollmentEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const enrollmentService = {
    async listEnrollments(): Promise<Enrollment[]> {
        try {
            const response = await enrollmentHttp.get<EnrollmentEnvelope<Enrollment[]>>('/enrollments');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async createEnrollment(payload: Record<string, unknown>): Promise<Enrollment> {
        try {
            const response = await enrollmentHttp.post<EnrollmentEnvelope<Enrollment>>('/enrollments', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async updateEnrollment(id: number, payload: Record<string, unknown>): Promise<Enrollment> {
        try {
            const response = await enrollmentHttp.put<EnrollmentEnvelope<Enrollment>>(`/enrollments/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async deleteEnrollment(id: number): Promise<void> {
        try {
            await enrollmentHttp.delete(`/enrollments/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async listTeacherAssignments(): Promise<TeacherAssignment[]> {
        try {
            const response = await enrollmentHttp.get<EnrollmentEnvelope<TeacherAssignment[]>>('/teacher-assignments');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async createTeacherAssignment(payload: Record<string, unknown>): Promise<TeacherAssignment> {
        try {
            const response = await enrollmentHttp.post<EnrollmentEnvelope<TeacherAssignment>>('/teacher-assignments', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async updateTeacherAssignment(id: number, payload: Record<string, unknown>): Promise<TeacherAssignment> {
        try {
            const response = await enrollmentHttp.put<EnrollmentEnvelope<TeacherAssignment>>(`/teacher-assignments/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async deleteTeacherAssignment(id: number): Promise<void> {
        try {
            await enrollmentHttp.delete(`/teacher-assignments/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
