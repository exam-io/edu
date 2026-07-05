import axios, { AxiosError } from 'axios';
import type { NavigationConfig, TenantDomain, WhiteLabelEnvelope } from '@modules/white-label/types/whiteLabel';

const whiteLabelHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<WhiteLabelEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const whiteLabelService = {
    async domains(): Promise<TenantDomain[]> {
        try {
            const response = await whiteLabelHttp.get<WhiteLabelEnvelope<TenantDomain[]>>('/white-label/domains');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async createDomain(payload: { host: string; is_primary?: boolean }): Promise<TenantDomain> {
        try {
            const response = await whiteLabelHttp.post<WhiteLabelEnvelope<TenantDomain>>('/white-label/domains', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async navigation(): Promise<NavigationConfig> {
        try {
            const response = await whiteLabelHttp.get<WhiteLabelEnvelope<NavigationConfig>>('/white-label/navigation');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
