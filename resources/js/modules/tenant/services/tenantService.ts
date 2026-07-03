import axios, { AxiosError } from 'axios';
import type {
    TenantBootstrapData,
    TenantEnvelope,
    ProvisionTenantPayload,
    Tenant,
} from '@modules/tenant/types/tenant';

const tenantHttp = axios.create({
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

    const payload = error.response?.data as Partial<TenantEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: TenantEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }
    return envelope.data;
}

export const tenantService = {
    /**
     * Get current tenant with branding and configuration.
     */
    async getCurrentTenant(): Promise<TenantBootstrapData> {
        try {
            const response = await tenantHttp.get<TenantEnvelope<TenantBootstrapData>>(
                '/tenants/current'
            );
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    /**
     * Get a specific tenant by ID.
     */
    async getTenant(id: number): Promise<Tenant> {
        try {
            const response = await tenantHttp.get<TenantEnvelope<Tenant>>(`/tenants/${id}`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    /**
     * Provision a new tenant.
     */
    async provisioning(payload: ProvisionTenantPayload): Promise<Tenant> {
        try {
            const response = await tenantHttp.post<TenantEnvelope<Tenant>>(
                '/tenants/provision',
                payload
            );
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    /**
     * Update tenant settings.
     */
    async updateSettings(id: number, settings: Partial<ProvisionTenantPayload>): Promise<void> {
        try {
            await tenantHttp.put<TenantEnvelope<null>>(`/tenants/${id}/settings`, settings);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
