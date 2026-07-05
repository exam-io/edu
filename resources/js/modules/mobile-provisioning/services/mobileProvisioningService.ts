import axios, { AxiosError } from 'axios';
import type { MobileBuildRequest, MobileConfig, MobileEnvelope } from '@modules/mobile-provisioning/types/mobileProvisioning';

const mobileHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<MobileEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const mobileProvisioningService = {
    async config(): Promise<MobileConfig> {
        try {
            const response = await mobileHttp.get<MobileEnvelope<MobileConfig>>('/mobile-provisioning/config');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async publish(): Promise<MobileConfig> {
        try {
            const response = await mobileHttp.post<MobileEnvelope<MobileConfig>>('/mobile-provisioning/publish');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async requests(): Promise<MobileBuildRequest[]> {
        try {
            const response = await mobileHttp.get<MobileEnvelope<MobileBuildRequest[]>>('/mobile-provisioning/requests');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
