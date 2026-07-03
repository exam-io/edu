export interface Course {
    id: number;
    tenant_id: number;
    title: string;
    code: string;
    description: string | null;
    level: 'beginner' | 'intermediate' | 'advanced';
    duration_minutes: number;
    price: number;
    status: 'draft' | 'published' | 'archived';
    published_at: string | null;
}

export interface CourseEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}
