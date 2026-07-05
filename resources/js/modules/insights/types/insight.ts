export interface GeneratedInsight {
    id: number;
    title: string;
    summary: string;
    severity: string;
    generated_at?: string | null;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}
