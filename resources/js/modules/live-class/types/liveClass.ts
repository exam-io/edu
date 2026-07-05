export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface LiveClassSession {
    id: number;
    title: string;
    description?: string | null;
    host_user_id: number;
    meeting_url?: string | null;
    scheduled_start_at: string;
    scheduled_end_at: string;
    status: 'scheduled' | 'live' | 'ended' | 'cancelled';
}

export interface PaginationMeta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}
