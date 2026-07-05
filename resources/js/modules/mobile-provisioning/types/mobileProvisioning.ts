export interface MobileConfig {
    id: number;
    tenant_id: number;
    version: number;
    config: Record<string, unknown>;
    published_at?: string | null;
}

export interface MobileBuildRequest {
    id: number;
    tenant_id: number;
    platform: string;
    status: string;
    notes?: string | null;
    requested_at?: string | null;
}

export interface MobileEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}
