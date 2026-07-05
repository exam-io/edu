export interface AnalyticsEvent {
    id: number;
    event_name: string;
    source_module: string;
    entity_type?: string | null;
    entity_id?: number | null;
    occurred_at?: string | null;
    payload?: Record<string, unknown>;
}

export interface MetricSnapshot {
    id: number;
    metric_key: string;
    dimension_key?: string | null;
    dimension_value?: string | null;
    metric_value: number;
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
