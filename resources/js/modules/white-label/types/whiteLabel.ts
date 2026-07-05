export interface TenantDomain {
    id: number;
    tenant_id: number;
    host: string;
    is_primary: boolean;
    status: string;
    verification_token?: string | null;
}

export interface NavigationConfig {
    id: number;
    tenant_id: number;
    version: number;
    items: Array<{ key: string; label: string; path: string; enabled: boolean; feature?: string | null }>;
}

export interface WhiteLabelEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}
