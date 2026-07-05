export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface TenantNotification {
    id: number;
    title: string;
    body?: string | null;
    notification_type: string;
    created_at: string;
    read_at?: string | null;
}
