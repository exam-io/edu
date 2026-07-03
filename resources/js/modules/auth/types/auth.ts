export interface AuthSettings {
    language: string;
    theme: string;
    timezone: string;
}

export interface AuthTenant {
    id: number;
    name: string;
    slug: string;
    domain: string;
}

export interface AuthUser {
    id: number;
    tenant_id: number | null;
    name: string;
    email: string;
    status: string;
    email_verified_at: string | null;
    last_login_at: string | null;
    last_login_ip: string | null;
    settings: AuthSettings;
}

export interface AuthContext {
    user: AuthUser;
    tenant: AuthTenant | null;
    roles: string[];
    permissions: string[];
    can_manage_identity: boolean;
}

export interface LoginPayload {
    email: string;
    password: string;
    remember?: boolean;
    tenant_id?: number;
}

export interface RegisterPayload {
    tenant_id: number;
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role?: string;
}

export interface ResetPasswordPayload {
    email: string;
    token: string;
    password: string;
    password_confirmation: string;
}

export interface VerifyEmailPayload {
    user_id: number;
    hash: string;
}

export interface AuthEnvelope<TData> {
    success: boolean;
    message: string;
    data: TData;
    errors: Record<string, string[]> | null;
}
