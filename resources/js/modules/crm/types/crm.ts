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

export interface Lead {
    id: number;
    first_name: string;
    last_name?: string | null;
    full_name: string;
    email: string;
    phone?: string | null;
    source: string;
    status: string;
    interest?: string | null;
    notes?: string | null;
}
