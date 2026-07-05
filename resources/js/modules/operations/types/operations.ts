export interface BackupExecution {
    id: number;
    status: string;
    storage_disk: string | null;
    storage_path: string | null;
    started_at: string | null;
    completed_at: string | null;
    meta: Record<string, unknown>;
}

export interface QueueOperationLog {
    id: number;
    operation: string;
    status: string;
    meta: Record<string, unknown>;
    executed_at: string | null;
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
