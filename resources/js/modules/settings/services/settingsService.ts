import axios, { AxiosError } from 'axios';
import type { SettingsEnvelope, UserSetting } from '@modules/settings/types/setting';

const settingsHttp = axios.create({
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

    const payload = error.response?.data as Partial<SettingsEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: SettingsEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const settingsService = {
    async current(): Promise<UserSetting> {
        try {
            const response = await settingsHttp.get<SettingsEnvelope<UserSetting>>('/settings');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async update(settingId: number, payload: Pick<UserSetting, 'language' | 'theme' | 'timezone'>): Promise<UserSetting> {
        try {
            const response = await settingsHttp.put<SettingsEnvelope<UserSetting>>(`/settings/${settingId}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};
