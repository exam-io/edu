import axios, { AxiosError } from 'axios';
import type { BillingCenter, BillingEnvelope, BillingProfile, Invoice } from '@modules/billing/types/billing';

const billingHttp = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readError(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<BillingEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

export const billingService = {
    async center(): Promise<BillingCenter> {
        try {
            const response = await billingHttp.get<BillingEnvelope<BillingCenter>>('/billing/center');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async invoices(): Promise<Invoice[]> {
        try {
            const response = await billingHttp.get<BillingEnvelope<Invoice[]>>('/billing/invoices');
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },

    async upsertProfile(payload: Partial<BillingProfile>): Promise<BillingProfile> {
        try {
            const response = await billingHttp.put<BillingEnvelope<BillingProfile>>('/billing/profile', payload);
            return response.data.data;
        } catch (error) {
            throw new Error(readError(error));
        }
    },
};
