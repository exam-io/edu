export interface UserSetting {
    id: number;
    user_id: number;
    tenant_id: number;
    language: 'en' | 'hi';
    theme: 'light' | 'dark';
    timezone: string;
    created_at: string;
    updated_at: string;
}

export interface SettingsEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
    errors?: Record<string, string[]> | null;
}
