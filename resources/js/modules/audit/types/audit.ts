export interface AuditLogEntry {
    id: number;
    actor_type: string;
    action: string;
    resource_type: string;
    resource_id: string | null;
    occurred_at: string;
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
