export interface SecurityPolicy {
    id: number;
    force_mfa: boolean;
    session_ttl_minutes: number;
    password_rotation_days: number;
    allow_ip_restriction: boolean;
    allowed_ip_ranges: string[];
    strict_transport_security: boolean;
}

export interface SystemHealthCheck {
    id: number;
    check_name: string;
    status: string;
    checked_at: string;
    meta: Record<string, unknown>;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}
