import axios, { AxiosError } from 'axios';
import type { AdmissionApplication, ApiEnvelope } from '@modules/admissions/types/admissions';

const http = axios.create({ baseURL: '/api', withCredentials: true, headers: { Accept: 'application/json' } });

function errorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const admissionsService = {
    async list(): Promise<AdmissionApplication[]> {
        try {
            const response = await http.get<ApiEnvelope<AdmissionApplication[]>>('/admissions/applications');
            return response.data.data;
        } catch (error) {
            throw new Error(errorMessage(error));
        }
    },
};
