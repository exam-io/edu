export interface BrandingProfile {
    id: number;
    tenant_id: number;
    name?: string | null;
    logo_url?: string | null;
    favicon_url?: string | null;
    primary_color?: string | null;
    secondary_color?: string | null;
    accent_color?: string | null;
    font_family?: string | null;
    theme_mode: 'light' | 'dark' | 'system';
    extra_tokens: Record<string, string>;
}

export interface ThemePayload {
    theme_mode: string;
    tokens: Record<string, string>;
}

export interface BrandingEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}
