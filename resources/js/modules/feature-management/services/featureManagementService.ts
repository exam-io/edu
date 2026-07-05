import axios, { AxiosError } from 'axios';
import type { FeatureCatalogEntry, FeatureEnvelope, FeatureFlag } from '@modules/feature-management/types/featureManagement';

const featureHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<FeatureEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const featureManagementService = {
    async catalog(): Promise<FeatureCatalogEntry[]> {
        try {
            const response = await featureHttp.get<FeatureEnvelope<FeatureCatalogEntry[]>>('/feature-management/catalog');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async flags(): Promise<FeatureFlag[]> {
        try {
            const response = await featureHttp.get<FeatureEnvelope<FeatureFlag[]>>('/feature-management/flags');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async update(flags: Array<{ feature_key: string; enabled: boolean; source?: string }>): Promise<FeatureFlag[]> {
        try {
            const response = await featureHttp.put<FeatureEnvelope<FeatureFlag[]>>('/feature-management/flags', { flags });
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
