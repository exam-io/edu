import axios, { AxiosError } from 'axios';
import type { BrandingEnvelope, BrandingProfile, ThemePayload } from '@modules/branding/types/branding';

const brandingHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<BrandingEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const brandingService = {
    async current(): Promise<BrandingProfile> {
        try {
            const response = await brandingHttp.get<BrandingEnvelope<BrandingProfile>>('/branding/current');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async update(payload: Partial<BrandingProfile>): Promise<BrandingProfile> {
        try {
            const response = await brandingHttp.put<BrandingEnvelope<BrandingProfile>>('/branding/current', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async theme(): Promise<ThemePayload> {
        try {
            const response = await brandingHttp.get<BrandingEnvelope<ThemePayload>>('/branding/theme');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
