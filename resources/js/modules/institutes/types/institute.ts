export interface AcademicSession {
    id: number;
    institute_id: number;
    name: string;
    code: string;
    starts_on: string;
    ends_on: string;
    is_current: boolean;
    status: 'planned' | 'active' | 'closed';
    metadata: Record<string, unknown> | null;
    created_at: string;
    updated_at: string;
}

export interface Institute {
    id: number;
    tenant_id: number;
    name: string;
    slug: string;
    code: string;
    status: 'provisioning' | 'active' | 'inactive' | 'archived';
    email: string | null;
    phone: string | null;
    website: string | null;
    description: string | null;
    address: Record<string, unknown> | null;
    branding: {
        logo_url: string | null;
        primary_color: string;
        secondary_color: string;
        tagline?: string | null;
    };
    configuration: {
        timezone: string;
        locale: string;
        features?: string[];
    };
    onboarding_step: string;
    onboarded_at: string | null;
    current_academic_session?: AcademicSession | null;
    created_at: string;
    updated_at: string;
}

export interface OnboardingStep {
    key: string;
    title: string;
    completed: boolean;
}

export interface OnboardingWizard {
    current_step: string;
    is_completed: boolean;
    steps: OnboardingStep[];
}

export interface InstituteEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
    errors?: Record<string, string[]> | null;
}

export interface RegisterInstitutePayload {
    name: string;
    slug?: string;
    code: string;
    email?: string;
    phone?: string;
    website?: string;
    description?: string;
    address?: Record<string, unknown>;
    primary_color?: string;
    secondary_color?: string;
    timezone?: string;
    locale?: string;
}

export interface UpdateInstitutePayload {
    name?: string;
    code?: string;
    email?: string | null;
    phone?: string | null;
    website?: string | null;
    description?: string | null;
    address?: Record<string, unknown> | null;
    timezone?: string;
    locale?: string;
    features?: string[];
}

export interface UpdateBrandingPayload {
    logo_url?: string | null;
    primary_color?: string;
    secondary_color?: string;
    tagline?: string | null;
}

export interface CreateAcademicSessionPayload {
    name: string;
    code: string;
    starts_on: string;
    ends_on: string;
    is_current?: boolean;
    status?: 'planned' | 'active' | 'closed';
    metadata?: Record<string, unknown>;
}
