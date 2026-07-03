export interface TenantBranding {
    primary_color: string;
    secondary_color: string;
    logo?: string;
    favicon?: string;
    theme: 'light' | 'dark';
    language: string;
    timezone: string;
}

export interface TenantConfiguration {
    max_users: number;
    max_storage: number;
    features: string[];
    custom_domain?: string;
    primary_domain: string;
    language: string;
    timezone: string;
    theme: string;
}

export interface TenantSettings {
    tenant_id: number;
    theme: string;
    language: string;
    timezone: string;
    logo?: string;
    favicon?: string;
    primary_color: string;
    secondary_color: string;
}

export interface Tenant {
    id: number;
    name: string;
    slug: string;
    domain: string;
    custom_domain?: string;
    status: 'active' | 'suspended' | 'inactive' | 'provisioning';
    plan: string;
    provisioned_at?: string;
    suspended_at?: string;
    settings?: TenantSettings;
    created_at: string;
    updated_at: string;
}

export interface TenantBootstrapData {
    tenant: Tenant;
    branding: TenantBranding;
    configuration: TenantConfiguration;
}

export interface TenantEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
    errors?: Record<string, string[]> | null;
}

export interface ProvisionTenantPayload {
    name: string;
    slug: string;
    domain: string;
    custom_domain?: string;
    plan?: 'free' | 'pro' | 'enterprise';
    theme?: 'light' | 'dark';
    language?: string;
    timezone?: string;
    primary_color?: string;
    secondary_color?: string;
}
