import axios, { AxiosError } from 'axios';
import type {
    AcademicSession,
    CreateAcademicSessionPayload,
    Institute,
    InstituteEnvelope,
    OnboardingWizard,
    RegisterInstitutePayload,
    UpdateBrandingPayload,
    UpdateInstitutePayload,
} from '@modules/institutes/types/institute';

const instituteHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<InstituteEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: InstituteEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const instituteService = {
    async register(payload: RegisterInstitutePayload): Promise<Institute> {
        try {
            const response = await instituteHttp.post<InstituteEnvelope<Institute>>('/institutes/register', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async getCurrent(): Promise<Institute> {
        try {
            const response = await instituteHttp.get<InstituteEnvelope<Institute>>('/institutes/current');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async getById(id: number): Promise<Institute> {
        try {
            const response = await instituteHttp.get<InstituteEnvelope<Institute>>(`/institutes/${id}`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update(id: number, payload: UpdateInstitutePayload): Promise<Institute> {
        try {
            const response = await instituteHttp.put<InstituteEnvelope<Institute>>(`/institutes/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async updateBranding(id: number, payload: UpdateBrandingPayload): Promise<Institute> {
        try {
            const response = await instituteHttp.patch<InstituteEnvelope<Institute>>(`/institutes/${id}/branding`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async getOnboarding(id: number): Promise<OnboardingWizard> {
        try {
            const response = await instituteHttp.get<InstituteEnvelope<OnboardingWizard>>(`/institutes/${id}/onboarding`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async listAcademicSessions(instituteId: number): Promise<AcademicSession[]> {
        try {
            const response = await instituteHttp.get<InstituteEnvelope<AcademicSession[]>>(
                `/institutes/${instituteId}/academic-sessions`
            );
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async createAcademicSession(instituteId: number, payload: CreateAcademicSessionPayload): Promise<AcademicSession> {
        try {
            const response = await instituteHttp.post<InstituteEnvelope<AcademicSession>>(
                `/institutes/${instituteId}/academic-sessions`,
                payload
            );
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async updateAcademicSession(
        instituteId: number,
        sessionId: number,
        payload: Partial<CreateAcademicSessionPayload>
    ): Promise<AcademicSession> {
        try {
            const response = await instituteHttp.patch<InstituteEnvelope<AcademicSession>>(
                `/institutes/${instituteId}/academic-sessions/${sessionId}`,
                payload
            );
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async deleteAcademicSession(instituteId: number, sessionId: number): Promise<void> {
        try {
            await instituteHttp.delete(`/institutes/${instituteId}/academic-sessions/${sessionId}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
